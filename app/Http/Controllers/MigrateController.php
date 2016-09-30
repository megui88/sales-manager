<?php

namespace App\Http\Controllers;

use App\Concept;
use App\Contract\States;
use App\Http\Requests\PharmacyFileRequest;
use App\Migrate;
use App\Periods;
use App\Sale;
use App\User;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\File\File;

class MigrateController extends Controller
{
    public function pharmacyFile(PharmacyFileRequest $request)
    {
        $file = $request->{'pharmacy-file'};
        $migrate = Migrate::create([
            'name' => $file->getClientOriginalName(),
            'checksum' => md5_file($file->getRealPath()),
            'description' => $request->get('description'),
            'status' => States::PENDING,
        ]);


        if (($gestor = fopen($file->getRealPath(), "r")) !== FALSE) {
            $buffer = [];
            while (!feof($gestor)) {
                try {
                    $line = fgets($gestor);
                    $data = explode("\t", $line);
                    $user = User::where('code','=',trim($data[0]))->first();

                    if (! $user || empty($data[2])) {
                        $buffer[] = $line;
                        continue;
                    }
                    $amount = trim(str_replace(',', '.', trim(str_replace('.', '', $data[2]))));
                    $sale = Sale::create([
                        'sale_mode' => $request->get('sale_mode'),
                        'payer_id' => $user->id,
                        'collector_id' => 0,
                        'period' => Periods::getCurrentPeriod(),
                        'concept_id' => Concept::where('name', '=', 'Venta Farmacia')->first()->id,
                        'description' => $request->get('description'),
                        'installments' => 1,
                        'charge' => 100,
                        'state' => Sale::INITIATED,
                        'amount' => $amount,
                        'migrate_id' => $migrate->id,
                    ]);
                } catch (\Exception $e) {
                    $buffer[] = $line . "\t" . $e->getMessage() . " " . $e->getFile() . " " .$e->getLine();
                }
            }
            fclose($gestor);

            if (!empty($buffer)) {
                $migrate->update([
                    'errors' => $buffer,
                    'status' => States::PROCESSED,
                ]);
                request()->session()->flash('alert-warning', 'La importación fue parcial.');
                return redirect()->to('/pharmacy');
            }

        } else {
            $migrate->update([
                'status' => States::STOPPED,
            ]);
            request()->session()->flash('alert-danger', 'No se puede leer el archivo');
            return redirect()->to('/pharmacy');
        }

        $migrate->update([
            'status' => States::CLOSED,
        ]);
        request()->session()->flash('alert-success', 'Importado correctamente.');
        return redirect()->to('/pharmacy');
    }

    public function errorsFile(Migrate $migrate)
    {
        return Response::make(implode('',$migrate->errors))->header("Content-type"," charset=utf-8")->header("Content-disposition","attachment; filename=\"error-".$migrate->name."\"");
    }
}
