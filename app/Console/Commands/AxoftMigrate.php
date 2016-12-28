<?php

namespace App\Console\Commands;

use App\Concept;
use App\Periods;
use App\Sale;
use App\Services\BusinessCore;
use App\TempAxoftMig;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Input\InputOption;
use \DB;

class AxoftMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'axoft:migrate';

    protected $_user = [];

    protected $_dates = [];

    protected $_noProcess = [1,2,5,11,51,53,54,59,115,201,400,401,403,404,405,406,407,408,409,999999];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable/Disable User by code';

    public function configure()
    {
        $this->addOption('id', null, InputOption::VALUE_REQUIRED, 'id migrate is required');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $operator = User::where(
            'role', '=', \App\Services\BusinessCore::EMPLOYEE_ADMIN_ROLE
        )->first();
        Auth::login($operator);
        $this->_currentAccount();
        $this->_pharmacySelling();
        $this->_NCpharmacySelling();
        $this->_Subsidy();
        $this->_revr();
        $this->_revrM();
        $this->_currentAccountMultiProvider();
        $this->_revrMulti();
        $this->_revrMutualMulti();
        return;
    }

    private function _Subsidy()
    {
        $comprobantes = DB::table('temp_axoft_mig')
            ->select(DB::raw('comprobante'), DB::raw('count(*) as counting'))
            ->where('process', '=', 0)
            ->where('fecha', '>=', '2016-06-14')
            ->where('cod_comprobante', '=', 'EGR')
            ->where('debe', '<>', 0)
            ->whereIn('cod_cuenta', [59])
          //  ->whereNotIn('cod_cuenta', [2])
            ->groupBy('comprobante')
            ->having('counting', '=', 1)
            ->get();
        $concepts = Concept::where('name', '=', 'Subsidio')->first()->id;

        foreach($comprobantes as $comprobante){
            try {
                $comp_user = TempAxoftMig::where('cod_comprobante', '=', 'EGR')
                    ->where('debe', '<>', 0)
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->whereIn('cod_cuenta', [59])
                  //  ->whereNotIn('cod_cuenta', [2])
                    ->first();
                if (!$comp_user) {
                    echo 'EGR' . $comprobante->comprobante . __METHOD__ . ' ' . __LINE__ .PHP_EOL;
                    continue; //no process.
                }

                $comp_collectors = TempAxoftMig::where('cod_comprobante', '=', 'EGR')
                    ->where('haber', '<>', 0)
                    ->whereNotIn('cod_cuenta', [2])
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->get();

                if (count($comp_collectors) == 0) {
                    echo 'EGR' . $comprobante->comprobante . __METHOD__ . ' ' . __LINE__ .PHP_EOL;
                    continue; //no process N/C desde mutual.
                }


                foreach ($comp_collectors as $comp_collector) {
                    if(!$comp_collector->user_id){
                        dd($comp_collector);
                    }
                    $collector = $this->getUser($comp_collector->user_id);

                    $sale =  Sale::create([
                        'sale_mode' => Sale::SUBSIDY,
                        'payer_id' => 0,
                        'collector_id' => $collector->id,
                        'created_at' => $comp_collector->fecha,
                        'period' => $this->_getPeriods($comp_collector->fecha),
                        'concept_id' => $concepts,
                        'description' => '[ EGR' . $comp_collector->comprobante . '] ' . $comp_collector->leyenda,
                        'installments' => 1,
                        'charge' => 0,
                        'state' => Sale::INITIATED,
                        'amount' => $comp_collector->haber,
                    ]);
                    $comp_collector->update([
                        'process' => 1
                    ]);
                }
                $comp_user->update([
                    'process' => 1
                ]);

            }catch (\Exception $e){
                dd($e->getFile(),$e->getLine(),$e->getMessage(),$comprobante->comprobante);
            }
        }
    }

    private function _pharmacySelling()
    {
        $comprobantes = DB::table('temp_axoft_mig')
            ->select(DB::raw('comprobante'), DB::raw('count(*) as counting'))
            ->where('process', '=', 0)
            ->where('fecha', '>=', '2016-06-14')
            ->where('cod_comprobante', '=', 'EGR')
            ->where('haber', '<>', 0)
            ->whereIn('cod_cuenta', [53])
            ->whereNotIn('cod_cuenta', [2])
            ->groupBy('comprobante')
            ->having('counting', '=', 1)
            ->get();
        $concepts = [
            1 => Concept::where('name', '=', 'Venta Farmacia')->first()->id,
            -1 => Concept::where('name', '=', 'Nota de Credito')->first()->id
        ];

        foreach($comprobantes as $comprobante){
            try {
                $comp_user = TempAxoftMig::where('cod_comprobante', '=', 'EGR')
                    ->where('haber', '<>', 0)
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->whereIn('cod_cuenta', [53])
                    ->whereNotIn('cod_cuenta', [2])
                    ->first();
                if (!$comp_user) {
                    echo 'EGR' . $comprobante->comprobante . __METHOD__ . ' ' . __LINE__ .PHP_EOL;
                    continue; //no process.
                }

                $comp_payers = TempAxoftMig::where('cod_comprobante', '=', 'EGR')
                    ->where('debe', '<>', 0)
                    ->whereNotIn('cod_cuenta', [2])
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->get();

                if (count($comp_payers) == 0) {
                    echo 'EGR' . $comprobante->comprobante . __METHOD__ . ' ' . __LINE__ .PHP_EOL;
                    continue; //no process N/C desde mutual.
                }


                foreach ($comp_payers as $comp_payer) {
                    if(!$comp_payer->user_id){
                        dd($comp_payer);
                    }
                    $payer = $this->getUser($comp_payer->user_id);
                    $mode = ($payer->role != BusinessCore::VENDOR_ROLE) ? 1 : -1;

                    Sale::create([
                        'sale_mode' => Sale::PHARMACY_SELLING,
                        'payer_id' => ($mode === 1) ? $payer->id : 0,
                        'collector_id' => ($mode === 1) ? 0 : $payer->id,
                        'period' => $this->_getPeriods($comp_payer->fecha),
                        'concept_id' => $concepts[$mode],
                        'description' => '[ EGR' . $comp_payer->comprobante . '] ' . $comp_payer->leyenda,
                        'installments' => 1,
                        'charge' => 100,
                        'state' => Sale::INITIATED,
                        'amount' => $mode * $comp_payer->debe,
                    ]);
                    $comp_payer->update([
                        'process' => 1
                    ]);
                }
                $comp_user->update([
                    'process' => 1
                ]);

            }catch (\Exception $e){
                dd($e->getFile(),$e->getLine(),$e->getMessage(),$comprobante->comprobante);
            }
        }
    }

    private function _NCpharmacySelling()
    {
        $comprobantes = DB::table('temp_axoft_mig')
            ->select(DB::raw('comprobante'), DB::raw('count(*) as counting'))
            ->where('process', '=', 0)
            ->where('fecha', '>=', '2016-06-14')
            ->where('cod_comprobante', '=', 'EGR')
            ->where('debe', '<>', 0)
            ->whereIn('cod_cuenta', [53])
            ->whereNotIn('cod_cuenta', [2])
            ->groupBy('comprobante')
            ->having('counting', '=', 1)
            ->get();
        $concepts = [
            1 => Concept::where('name', '=', 'Venta Farmacia')->first()->id,
            -1 => Concept::where('name', '=', 'Nota de Credito')->first()->id
        ];

        foreach($comprobantes as $comprobante){
            try {
                $comp_user = TempAxoftMig::where('cod_comprobante', '=', 'EGR')
                    ->where('debe', '<>', 0)
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->whereIn('cod_cuenta', [53])
                    ->whereNotIn('cod_cuenta', [2])
                    ->first();
                if (!$comp_user) {
                    echo 'EGR' . $comprobante->comprobante . __METHOD__ . ' ' . __LINE__ .PHP_EOL;
                    continue; //no process.
                }

                $comp_payers = TempAxoftMig::where('cod_comprobante', '=', 'EGR')
                    ->where('haber', '<>', 0)
                    ->whereNotIn('cod_cuenta', [2])
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->get();

                if (count($comp_payers) == 0) {
                    echo 'EGR' . $comprobante->comprobante . __METHOD__ . ' ' . __LINE__ .PHP_EOL;
                    continue; //no process N/C desde mutual.
                }


                foreach ($comp_payers as $comp_payer) {
                    if(!$comp_payer->user_id){
                        dd($comp_payer);
                    }
                    $payer = $this->getUser($comp_payer->user_id);

                    Sale::create([
                        'sale_mode' => Sale::PHARMACY_SELLING,
                        'payer_id' => $payer->id,
                        'collector_id' => 0,
                        'period' => $this->_getPeriods($comp_payer->fecha),
                        'concept_id' => $concepts[-1],
                        'description' => '[ EGR' . $comp_payer->comprobante . '] ' . $comp_payer->leyenda,
                        'installments' => 1,
                        'charge' => 100,
                        'state' => Sale::INITIATED,
                        'amount' => -1 * $comp_payer->debe,
                    ]);
                    $comp_payer->update([
                        'process' => 1
                    ]);
                }
                $comp_user->update([
                    'process' => 1
                ]);

            }catch (\Exception $e){
                dd($e->getFile(),$e->getLine(),$e->getMessage(),$comprobante->comprobante);
            }
        }
    }

    private function _currentAccountMultiProvider()
    {
        $comprobantes = DB::table('temp_axoft_mig')
            ->select(DB::raw('comprobante'), DB::raw('count(*) as counting'))
            ->where('process','=', 0)
            ->where('fecha','>=', '2016-06-14')
            ->where('cod_comprobante', '=', 'EGR')
            ->where('haber', '<>', 0)
            ->whereNotIn('cod_cuenta',$this->_noProcess)
            ->groupBy('comprobante')
            ->having('counting', '>', 1)
            ->get();
        $concepts = [
            1 => Concept::where('name', '=', 'Venta Mutual')->first()->id,
            -1 => Concept::where('name', '=', 'Nota de Credito')->first()->id
        ];

        foreach($comprobantes as $comprobante){
            try {
                $comp_habers = TempAxoftMig::where('cod_comprobante', '=', 'EGR')
                    ->where('haber', '<>', 0)
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->whereNotIn('cod_cuenta', $this->_noProcess)
                    ->get();

                if (count($comp_habers) == 0) {
                    echo 'EGR' . $comprobante->comprobante . __METHOD__ . ' ' . __LINE__ .PHP_EOL;
                    continue; //no process.
                }

                foreach ($comp_habers as $comp_haber) {
                    $collector = $this->getUser($comp_haber->user_id);

                    $mode = ($collector->role == BusinessCore::VENDOR_ROLE) ? 1 : -1;
                    $sale = Sale::create([
                        'sale_mode' => Sale::CURRENT_ACCOUNT,
                        'payer_id' => 0,
                        'collector_id' => $collector->id,
                        'period' => $this->_getPeriods($comp_haber->fecha),
                        'concept_id' => $concepts[$mode],
                        'description' => '[ EGR' . $comp_haber->comprobante . '] ' . $comp_haber->leyenda,
                        'installments' => 1,
                        'charge' => $collector->administrative_expenses,
                        'state' => Sale::INITIATED,
                        'amount' => $mode * $comp_haber->haber,
                    ]);
                    if($sale->id) {
                        $comp_haber->update([
                            'process' => 1
                        ]);
                    }else{
                        echo 'EGR' . $comprobante->comprobante . __METHOD__ . ' ' . __LINE__ .PHP_EOL;
                    }
                }

                $comp_debes = TempAxoftMig::where('cod_comprobante', '=', 'EGR')
                    ->where('debe', '<>', 0)
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->whereNotIn('cod_cuenta', $this->_noProcess)
                    ->get();

                if (count($comp_debes) == 0) {
                    echo 'EGR' . $comprobante->comprobante . __METHOD__ . ' ' . __LINE__ .PHP_EOL;
                    continue; //no process N/C desde mutual.
                }

                foreach ($comp_debes as $comp_debe) {
                    $payer = $this->getUser($comp_debe->user_id);

                    $sale = Sale::create([
                        'sale_mode' => Sale::CURRENT_ACCOUNT,
                        'payer_id' => $payer->id,
                        'collector_id' => 0,
                        'period' => $this->_getPeriods($comp_debe->fecha),
                        'concept_id' => $concepts[1],
                        'description' => '[ EGR' . $comp_debe->comprobante . '] ' . $comp_debe->leyenda,
                        'installments' => 1,
                        'charge' => 100,
                        'state' => Sale::INITIATED,
                        'amount' => $comp_debe->debe,
                    ]);
                    if($sale->id) {
                        $comp_debe->update([
                            'process' => 1
                        ]);
                    }else{
                        echo $comprobante->comprobante . PHP_EOL;
                    }
                }

            }catch (\Exception $e){
                dd($e->getFile(),$e->getLine(),$e->getMessage(),$comprobante->comprobante);
            }
        }

    }

    private function _currentAccount()
    {
        $comprobantes = DB::table('temp_axoft_mig')
            ->select(DB::raw('comprobante'), DB::raw('count(*) as counting'))
            ->where('process','=', 0)
            ->where('fecha','>=', '2016-06-14')
            ->where('cod_comprobante', '=', 'EGR')
            ->where('haber', '<>', 0)
            ->whereNotIn('cod_cuenta',$this->_noProcess)
            ->groupBy('comprobante')
            ->having('counting', '=', 1)
            ->get();
        $concepts = [
            1 => Concept::where('name', '=', 'Venta Mutual')->first()->id,
            -1 => Concept::where('name', '=', 'Nota de Credito')->first()->id
        ];

        foreach($comprobantes as $comprobante){
            try {
                $comp_user = TempAxoftMig::where('cod_comprobante', '=', 'EGR')
                    ->where('haber', '<>', 0)
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->whereNotIn('cod_cuenta', $this->_noProcess)
                    ->first();
                if (!$comp_user || !$comp_user->user_id) {
                    throw new \Exception('no existe el comp_user id: ' . $comp_user->comprobante . ' ' . $comp_user->cod_cuenta . ' ' . $comp_user->user_id . ' .');
                }

                $comp_payers = TempAxoftMig::where('cod_comprobante', '=', 'EGR')
                    ->where('debe', '<>', 0)
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->whereNotIn('cod_cuenta', $this->_noProcess)
                    ->get();

                if (count($comp_payers) == 0) {
                    echo 'EGR' . $comprobante->comprobante . __METHOD__ . ' ' . __LINE__ .PHP_EOL;
                    continue; //no process N/C desde mutual.
                }

                $collector = $this->getUser($comp_user->user_id);

                $mode = ($collector->role == BusinessCore::VENDOR_ROLE) ? 1 : -1;

                foreach ($comp_payers as $comp_payer) {
                    $payer = $this->getUser($comp_payer->user_id);

                    Sale::create([
                        'sale_mode' => Sale::CURRENT_ACCOUNT,
                        'payer_id' => ($mode === 1) ? $payer->id : $collector->id,
                        'collector_id' => ($mode === 1) ? $collector->id : $payer->id,
                        'period' => $this->_getPeriods($comp_payer->fecha),
                        'concept_id' => $concepts[$mode],
                        'description' => '[ EGR' . $comp_payer->comprobante . '] ' . $comp_payer->leyenda,
                        'installments' => 1,
                        'charge' => $collector->administrative_expenses,
                        'state' => Sale::INITIATED,
                        'amount' => $mode * $comp_payer->debe,
                    ]);
                    $comp_payer->update([
                        'process' => 1
                    ]);
                }
                $comp_user->update([
                    'process' => 1
                ]);

            }catch (\Exception $e){
                dd($e->getFile(),$e->getLine(),$e->getMessage(),$comprobante->comprobante);
            }
        }

    }

    private function _revr()
    {
        $comprobantes = DB::table('temp_axoft_mig')
            ->select(DB::raw('comprobante'), DB::raw('count(*) as counting'))
            ->where('process','=', 0)
            ->where('fecha','>=', '2016-06-14')
            ->where('cod_comprobante', '=', 'REV')
            ->where('haber', '<>', 0)
            ->whereNotIn('cod_cuenta',$this->_noProcess)
            ->groupBy('comprobante')
            ->having('counting', '=', 1)
            ->get();
        $concept =  Concept::where('name', '=', 'Nota de Credito')->first()->id;

        foreach($comprobantes as $comprobante){
            try {
                $comp_user = TempAxoftMig::where('cod_comprobante', '=', 'REV')
                    ->where('haber', '<>', 0)
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->whereNotIn('cod_cuenta', $this->_noProcess)
                    ->first();
                if (!$comp_user || !$comp_user->user_id) {
                    throw new \Exception('no existe el comp_user id: ' . $comp_user->comprobante . ' ' . $comp_user->cod_cuenta . ' ' . $comp_user->user_id . ' .');
                }

                $comp_payers = TempAxoftMig::where('cod_comprobante', '=', 'REV')
                    ->where('debe', '<>', 0)
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->whereNotIn('cod_cuenta', $this->_noProcess)
                    ->get();

                if (count($comp_payers) == 0) {
                    echo 'REV' . $comprobante->comprobante . __METHOD__ . ' ' . __LINE__ .PHP_EOL;
                    continue; //no process N/C desde mutual.
                }

                $collector = $this->getUser($comp_user->user_id);

                foreach ($comp_payers as $comp_payer) {
                    if(!$comp_payer->user_id){
                        dd($comp_payer);
                    }
                    $payer = $this->getUser($comp_payer->user_id);

                    Sale::create([
                        'sale_mode' => Sale::CURRENT_ACCOUNT,
                        'payer_id' =>  $payer->id,
                        'collector_id' => $collector->id,
                        'period' => $this->_getPeriods($comp_payer->fecha),
                        'concept_id' => $concept,
                        'description' => '[ REV' . $comp_payer->comprobante . '] ' . $comp_payer->leyenda,
                        'installments' => 1,
                        'charge' => $collector->administrative_expenses,
                        'state' => Sale::INITIATED,
                        'amount' => $comp_payer->debe,
                    ]);
                    $comp_payer->update([
                        'process' => 1
                    ]);
                }
                $comp_user->update([
                    'process' => 1
                ]);

            }catch (\Exception $e){
                dd($e->getFile(),$e->getLine(),$e->getMessage(),$comprobante->comprobante);
            }
        }
    }

    private function _revrM()
    {
        $comprobantes = DB::table('temp_axoft_mig')
            ->select(DB::raw('comprobante'), DB::raw('count(*) as counting'))
            ->where('process','=', 0)
            ->where('fecha','>=', '2016-06-14')
            ->where('cod_comprobante', '=', 'REV')
            ->where('haber', '<>', 0)
            ->whereNotIn('cod_cuenta',$this->_noProcess)
            ->groupBy('comprobante')
            ->having('counting', '=', 1)
            ->get();
        $concept =  Concept::where('name', '=', 'Nota de Credito')->first()->id;

        foreach($comprobantes as $comprobante){
            try {
                $comp_user = TempAxoftMig::where('cod_comprobante', '=', 'REV')
                    ->where('haber', '<>', 0)
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->whereNotIn('cod_cuenta', $this->_noProcess)
                    ->first();
                if (!$comp_user || !$comp_user->user_id) {
                    throw new \Exception('no existe el comp_user id: ' . $comp_user->comprobante . ' ' . $comp_user->cod_cuenta . ' ' . $comp_user->user_id . ' .');
                }

                $comp_payers = TempAxoftMig::where('cod_comprobante', '=', 'REV')
                    ->where('debe', '<>', 0)
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->whereIn('cod_cuenta', [1, 201])
                    ->get();

                if (count($comp_payers) == 0) {
                    echo 'REV' . $comprobante->comprobante . __METHOD__ . ' ' . __LINE__ .PHP_EOL;
                    continue; //no process N/C desde mutual.
                }

                $collector = $this->getUser($comp_user->user_id);

                foreach ($comp_payers as $comp_payer) {
                    Sale::create([
                        'sale_mode' => Sale::CURRENT_ACCOUNT,
                        'payer_id' =>  0,
                        'collector_id' => $collector->id,
                        'period' => $this->_getPeriods($comp_payer->fecha),
                        'concept_id' => $concept,
                        'description' => '[ REV' . $comp_payer->comprobante . '] ' . $comp_payer->leyenda,
                        'installments' => 1,
                        'charge' => $collector->administrative_expenses,
                        'state' => Sale::INITIATED,
                        'amount' => $comp_payer->debe,
                    ]);
                    $comp_payer->update([
                        'process' => 1
                    ]);
                }
                $comp_user->update([
                    'process' => 1
                ]);

            }catch (\Exception $e){
                dd($e->getFile(),$e->getLine(),$e->getMessage(),$comprobante->comprobante);
            }
        }
    }

    private function _revrMulti()
    {
        $comprobantes = DB::table('temp_axoft_mig')
            ->select(DB::raw('comprobante'), DB::raw('count(*) as counting'))
            ->where('process','=', 0)
            ->where('fecha','>=', '2016-06-14')
            ->where('cod_comprobante', '=', 'REV')
            ->where('haber', '<>', 0)
            ->whereNotIn('cod_cuenta',$this->_noProcess)
            ->groupBy('comprobante')
            ->having('counting', '>', 1)
            ->get();
        $concept =  Concept::where('name', '=', 'Nota de Credito')->first()->id;

        foreach($comprobantes as $comprobante){
            try {
                $comp_habers = TempAxoftMig::where('cod_comprobante', '=', 'REV')
                    ->where('haber', '<>', 0)
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->whereNotIn('cod_cuenta', $this->_noProcess)
                    ->get();
                if (count($comp_habers) == 0) {
                    throw new \Exception('no funciona el comprobante: ' . $comprobante->comprobante );
                }
                foreach ($comp_habers as $comp_haber) {
                    if(!$comp_haber->user_id){
                        dd($comp_haber);
                    }
                    $collector = $this->getUser($comp_haber->user_id);

                    Sale::create([
                        'sale_mode' => Sale::CURRENT_ACCOUNT,
                        'payer_id' =>  0,
                        'collector_id' => $collector->id,
                        'period' => $this->_getPeriods($comp_haber->fecha),
                        'concept_id' => $concept,
                        'description' => '[ REV' . $comp_haber->comprobante . '] ' . $comp_haber->leyenda,
                        'installments' => 1,
                        'charge' => 0,
                        'state' => Sale::INITIATED,
                        'amount' => $comp_haber->debe,
                    ]);
                    $comp_haber->update([
                        'process' => 1
                    ]);
                }

                $comp_debes = TempAxoftMig::where('cod_comprobante', '=', 'REV')
                    ->where('debe', '<>', 0)
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->whereNotIn('cod_cuenta', $this->_noProcess)
                    ->get();

                if (count($comp_debes) == 0) {
                    echo 'REV' . $comprobante->comprobante . __METHOD__ . ' ' . __LINE__ .PHP_EOL;
                    continue; //no process N/C desde mutual.
                }
                foreach ($comp_debes as $comp_debe) {
                    if(!$comp_debe->user_id){
                        dd($comp_debe);
                    }
                    $payer = $this->getUser($comp_debe->user_id);

                    Sale::create([
                        'sale_mode' => Sale::CURRENT_ACCOUNT,
                        'payer_id' =>  $payer->id,
                        'collector_id' => 0,
                        'period' => $this->_getPeriods($comp_debe->fecha),
                        'concept_id' => $concept,
                        'description' => '[ REV' . $comp_debe->comprobante . '] ' . $comp_debe->leyenda,
                        'installments' => 1,
                        'charge' => 100,
                        'state' => Sale::INITIATED,
                        'amount' => $comp_debe->debe,
                    ]);
                    $comp_debe->update([
                        'process' => 1
                    ]);
                }

            }catch (\Exception $e){
                dd($e->getFile(),$e->getLine(),$e->getMessage(),$comprobante->comprobante);
            }
        }
    }

    private function _revrMutualMulti()
    {
        $comprobantes = DB::table('temp_axoft_mig')
            ->select(DB::raw('comprobante'), DB::raw('count(*) as counting'))
            ->where('process','=', 0)
            ->where('fecha','>=', '2016-06-14')
            ->where('cod_comprobante', '=', 'REV')
            ->where('haber', '<>', 0)
            ->whereNotIn('cod_cuenta',$this->_noProcess)
            ->groupBy('comprobante')
            ->having('counting', '>', 1)
            ->get();
        $concept =  Concept::where('name', '=', 'Nota de Credito')->first()->id;

        foreach($comprobantes as $comprobante){

            echo 'REV' . $comprobante->comprobante . __METHOD__ . ' ' . __LINE__ .PHP_EOL;
            continue; //no process N/C desde mutual.

            try {
                $comp_habers = TempAxoftMig::where('cod_comprobante', '=', 'REV')
                    ->where('haber', '<>', 0)
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->whereNotIn('cod_cuenta', $this->_noProcess)
                    ->first();
                if (0 == count($comp_habers)) {
                    throw new \Exception('no funciona el comprobante: ' . $comprobante->comprobante );
                }
                foreach ($comp_habers as $comp_haber){

                }

                $comp_debes = TempAxoftMig::where('cod_comprobante', '=', 'REV')
                    ->where('debe', '<>', 0)
                    ->where('comprobante', '=', $comprobante->comprobante)
                    ->whereIn('cod_cuenta', [1, 201])
                    ->get();

                if (count($comp_debes) == 0) {
                    echo 'REV' . $comprobante->comprobante . __METHOD__ . ' ' . __LINE__ .PHP_EOL;
                    continue; //no process N/C desde mutual.
                }


                foreach ($comp_debes as $comp_debe) {
                    if(!in_array($comp_debe->cod_cuenta, [1,201])){
                        $payerId = 0;
                    }else{
                        if(!$comp_debe->user_id){
                            dd($comp_debe);
                        }
                        $payerId = $comp_debe->user_id;
                    }
                    Sale::create([
                        'sale_mode' => Sale::CURRENT_ACCOUNT,
                        'payer_id' =>  $payerId,
                        'collector_id' => 0,
                        'period' => $this->_getPeriods($comp_debe->fecha),
                        'concept_id' => $concept,
                        'description' => '[ REV' . $comp_debe->comprobante . '] ' . $comp_debe->leyenda,
                        'installments' => 1,
                        'charge' => 0,
                        'state' => Sale::INITIATED,
                        'amount' => $comp_payer->debe,
                    ]);
                    $comp_payer->update([
                        'process' => 1
                    ]);
                }
                $comp_debe->update([
                    'process' => 1
                ]);

            }catch (\Exception $e){
                dd($e->getFile(),$e->getLine(),$e->getMessage(),$comprobante->comprobante);
            }
        }
    }

    private function _getPeriods($fecha)
    {
        if(!empty($this->_dates[$fecha])){
            return $this->_dates[$fecha];
        }
        if( $fecha > '2016-12-12' && $fecha < '2016-12-31'){
            $fecha = '2017-01-01';
        }

        return $this->_dates[$fecha] = Periods::getPeriod($fecha);
    }

    private function getUser($id)
    {
        if(!empty($this->_user[$id])){
            return $this->_user[$id];
        }
        $user = User::where('id','=',$id)->first();
        if(!$user){
            throw new \Exception('no existe el usuario id: ' . $id);
        }

        return $this->_user[$id] = $user;
    }
}
