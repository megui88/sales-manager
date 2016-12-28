<?php

namespace App\Http\Controllers;

use App\CodeCompatibilizer;
use App\Concept;
use App\Contract\States;
use App\Http\Requests\AxoftImportFileRequest;
use App\Http\Requests\BulkImportFileRequest;
use App\Http\Requests\PharmacyFileRequest;
use App\Migrate;
use App\Periods;
use App\Sale;
use App\TempAxoftMig;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class MigrateController extends Controller
{
    public function pharmacyFile(PharmacyFileRequest $request)
    {
        $file = $request->{'pharmacy-file'};
        $migrate = Migrate::create([
            'name' => $file->getClientOriginalName(),
            'type' => Migrate::PHARMACY_TYPE,
            'checksum' => md5_file($file->getRealPath()),
            'description' => $request->get('description'),
            'status' => States::PENDING,
        ]);

        $content = file_get_contents($file->getRealPath());

        function turn_array($m)
        {
            for ($z = 0;$z < count($m);$z++)
            {
                for ($x = 0;$x < count($m[$z]);$x++)
                {
                    $rt[$x][$z] = $m[$z][$x];
                }
            }

            return $rt;
        }
        preg_match_all('/\s+(\d+)\s+[a-zA-Z\,\.\s]+([\,\.\d]+)\s+/',$content, $tt);

        $results = turn_array($tt);
        $buffer = [];
        $concept_id = Concept::where('name', '=', 'Venta Farmacia')->first()->id;
        $period = Periods::getCurrentPeriod();
        foreach ($results as $item) {
            try {
                $user = User::where('code','=',trim($item[1]))
                    ->orWhere('code','=',trim($item[1]) . 0)->first();

                if (! $user) {
                    //find compatibilizer
                    $compativilizer = CodeCompatibilizer::where('codigo','=',trim($item[1]) . 0)->first();
                    if(!$compativilizer){

                        $buffer[] = trim($item[0]);
                        continue;
                    }
                    $user = User::where('code','=',$compativilizer->legajo)->first();
                    if(!$user){
                        $buffer[] = trim($item[0]);
                        continue;
                    }
                }
                if ( empty($item[2])) {
                    $buffer[] = trim($item[0]);
                    continue;
                }
                $amount = trim(str_replace(',', '.', trim(str_replace('.', '', $item[2]))));
                Sale::create([
                    'sale_mode' => $request->get('sale_mode'),
                    'payer_id' => $user->id,
                    'collector_id' => 0,
                    'period' => $period,
                    'concept_id' => $concept_id,
                    'description' => $request->get('description'),
                    'installments' => 1,
                    'charge' => 100,
                    'state' => Sale::INITIATED,
                    'amount' => $amount,
                    'migrate_id' => $migrate->id,
                ]);
            } catch (\Exception $e) {
                $buffer[] = trim($item[0]) . "\t" . $e->getMessage() . " " . $e->getFile() . " " .$e->getLine();
            }
        }

        if (!empty($buffer)) {
            $migrate->update([
                'errors' => $buffer,
                'status' => States::PROCESSED,
            ]);
            request()->session()->flash('alert-warning', 'La importación fue parcial.');
            return redirect()->to('/pharmacy');
        }

        $migrate->update([
            'status' => States::CLOSED,
        ]);

        request()->session()->flash('alert-success', 'Importado correctamente.');
        return redirect()->to('/pharmacy');
    }

    public function bulkImportFile(BulkImportFileRequest $request)
    {
        $file = $request->{'bulk-import-file'};
        $migrate = Migrate::create([
            'name' => $file->getClientOriginalName(),
            'type' => Migrate::BULK_TYPE,
            'checksum' => md5_file($file->getRealPath()),
            'description' => $request->get('description'),
            'status' => States::PENDING,
        ]);


        if (($gestor = fopen($file->getRealPath(), "r")) !== FALSE) {
            $buffer = [];
            $concept_id = Concept::where('name', '=', 'Venta Mutual')->first()->id;
            $period = Periods::getCurrentPeriod();
            while (!feof($gestor)) {
                try {
                    $line = fgets($gestor);
                    $data = explode(",", $line);

                    if (empty($data[0]) || empty($data[1])) {
                        $buffer[] = $line;
                        continue;
                    }
                    $user = User::where('code','=',trim($data[0]))->first();
                    $provider = User::where('code','=',trim($data[1]))->first();

                    if (! $user || ! $provider || empty($data[2]) || empty($data[3])) {
                        $buffer[] = $line;
                        continue;
                    }
                    $installments = trim((int)$data[2]);
                    $amount = trim(str_replace(',', '.', trim($data[3])));
                    Sale::create([
                        'sale_mode' => $request->get('sale_mode'),
                        'payer_id' => $user->id,
                        'collector_id' => 0,
                        'period' => $period,
                        'concept_id' => $concept_id,
                        'description' => $request->get('description'),
                        'installments' => $installments,
                        'charge' => $provider->administrative_expenses,
                        'state' => Sale::INITIATED,
                        'amount' => $amount,
                        'migrate_id' => $migrate->id,
                    ]);
                } catch (\Exception $e) {
                    $buffer[] = $line . "," . $e->getMessage() . " " . $e->getFile() . " " .$e->getLine();
                }
            }
            fclose($gestor);

            if (!empty($buffer)) {
                $migrate->update([
                    'errors' => $buffer,
                    'status' => States::PROCESSED,
                ]);
                request()->session()->flash('alert-warning', 'La importación fue parcial.');
                return redirect()->to('/bulk_import');
            }

        } else {
            $migrate->update([
                'status' => States::STOPPED,
            ]);
            request()->session()->flash('alert-danger', 'No se puede leer el archivo');
            return redirect()->to('/bulk_import');
        }

        $migrate->update([
            'status' => States::CLOSED,
        ]);
        request()->session()->flash('alert-success', 'Importado correctamente.');
        return redirect()->to('/bulk_import');
    }

    public function AxoftImportFile(AxoftImportFileRequest $request)
    {
        $file = $request->{'axoft-import-file'};
        $migrate = Migrate::create([
            'name' => $file->getClientOriginalName(),
            'type' => Migrate::AXOFT_TYPE,
            'checksum' => md5_file($file->getRealPath()),
            'description' => $request->get('description'),
            'status' => States::PENDING,
        ]);

        if (($gestor = fopen($file->getRealPath(), "r")) !== FALSE) {
            $buffer = [];
            while (!feof($gestor)) {
                try {
                    $line = fgets($gestor);
                    $data = explode("|", $line);

                    if (empty($data[0])) {
                        $buffer[] = trim($line) . "Falta 0 " . PHP_EOL;
                        continue;
                    }
                    if (empty($data[10])) {
                        $buffer[] = trim($line) . "Falta 10" . PHP_EOL;
                        continue;
                    }
                    //
                    $data[10] = ($data[10] == 310)? 39 :$data[10];
                    $data[10] = ($data[10] == 311)? 55 :$data[10];
                    $data[10] = ($data[10] == 348)? 328 :$data[10];
                    $data[10] = ($data[10] == 549)? 54 :$data[10];
                    $data[10] = ($data[10] == 333)? 517 :$data[10];
                    $data[10] = ($data[10] == 5)? 999999 :$data[10];
                    $data[10] = ($data[10] == 556)? 5 :$data[10];

                    $user = User::where('code', '=', trim($data[10]))
                        ->orWhere('code', '=', trim($data[10]) . 0)->first();
                    if(in_array($data[10],[54, 53, 51, 999999])){
                        $user = null;
                    }elseif (!$user) {
                        //find compatibilizer
                        $compativilizer = CodeCompatibilizer::where('codigo', '=', trim($data[10]) . 0)->first();
                        if ($compativilizer) {
                            $user = User::where('code', '=', $compativilizer->legajo)->first();
                        }
                    }

                    $debe = trim(str_replace(',','.',str_replace('.', '', trim($data[48]))));
                    $haber = trim(str_replace(',','.',str_replace('.', '', trim($data[49]))));
                    TempAxoftMig::create([
                        'migrate_id' => $migrate->id,
                        'fecha' => Carbon::createFromFormat('d/m/Y',$data[0]),
                        'user_id' => !$user ? null : $user->id,
                        'cod_cuenta' => $data[10],
                        'cod_comprobante' => $data[3],
                        'comprobante' => $data[5],
                        'leyenda' => isset($data[44])?$data[44]:'',
                        'debe' => $debe,
                        'haber' => $haber,
                    ]);
                } catch (\Exception $e) {
                    $buffer[] = trim($line) . ";" . $e->getMessage() . " " . $e->getFile() . " " .$e->getLine() . PHP_EOL;
                }
            }
            fclose($gestor);

            if (!empty($buffer)) {
                $migrate->update([
                    'errors' => $buffer,
                    'status' => States::PROCESSED,
                ]);
                request()->session()->flash('alert-warning', 'La importación fue parcial.');
                return redirect()->to('/axoft_import');
            }

        } else {
            $migrate->update([
                'status' => States::STOPPED,
            ]);
            request()->session()->flash('alert-danger', 'No se puede leer el archivo');
            return redirect()->to('/axoft_import');
        }

        $migrate->update([
            'status' => States::CLOSED,
        ]);
        request()->session()->flash('alert-success', 'Importado correctamente.');
        return redirect()->to('/axoft_import');
    }

    public function errorsFile(Migrate $migrate)
    {
        return Response::make(is_null($migrate->errors)?'':implode(PHP_EOL,$migrate->errors))->header("Content-type"," charset=utf-8")->header("Content-disposition","attachment; filename=\"error-".$migrate->name."\"");
    }
}
