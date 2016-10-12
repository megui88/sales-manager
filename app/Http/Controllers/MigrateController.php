<?php

namespace App\Http\Controllers;

use App\CodeCompatibilizer;
use App\Concept;
use App\Contract\States;
use App\Http\Requests\BulkImportFileRequest;
use App\Http\Requests\PharmacyFileRequest;
use App\Migrate;
use App\Periods;
use App\Sale;
use App\User;
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

    public function errorsFile(Migrate $migrate)
    {
        return Response::make(implode(PHP_EOL,$migrate->errors))->header("Content-type"," charset=utf-8")->header("Content-disposition","attachment; filename=\"error-".$migrate->name."\"");
    }
}
