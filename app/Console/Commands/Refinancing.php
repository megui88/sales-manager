<?php

namespace App\Console\Commands;

use App\User;
use App\Due;
use App\Sale;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;


class Refinancing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sm:refinancing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete/Annul migrate by id';

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
        $buffer = [];
        $collectors = [];
        $total = 0;
        /** @var Due[] $sales */
        $dues = Due::where('period', '>', '201711')
            ->whereNotIn('state', [Sale::ANNULLED, Sale::PENDING])
            ->where('payer_id', '!=', '47c94ba8-84a2-11e6-a97d-04011111c601')
            ->get();
        foreach ($dues as $due) {

            if (empty($buffer[$due->payer_id])) {
                $buffer[$due->payer_id] = [];
            }
            $sale = $due->sale;
            $collector = $sale->collector;
            $collectors[$collector->id] = $collector;
            if (empty($buffer[$due->payer_id][$collector->id])) {
                $buffer[$due->payer_id][$collector->id] = 0;
            }
            $buffer[$due->payer_id][$collector->id] += $due->amount_of_quota;
            $total += $due->amount_of_quota;

             Sale::create([
                  'sale_mode' => Sale::CURRENT_ACCOUNT,
                  'payer_id' => $due->payer_id,
                  'collector_id' => $collector->id,
                  'period' => $due->period,
                  'concept_id' => 'c418cb0e-7e10-11e6-91cb-04011111c601',
                  'description' => 'N/C Refinanciación de deuda mayor al 12/2017 solicitado por MP',
                  'installments' => 1,
                  'charge' => $collector->administrative_expenses,
                  'state' => Sale::INITIATED,
                  'amount' => -1 * $due->amount_of_quota,
              ]);
        }

        foreach ($buffer as $payerId => $newDues) {
            foreach ($newDues as $collectorId => $amount) {
                Sale::create([
                    'sale_mode' => Sale::CURRENT_ACCOUNT,
                    'payer_id' => $payerId,
                    'collector_id' => $collectorId,
                    'period' => '201707',
                    'concept_id' => 'c4179e00-7e10-11e6-b231-04011111c601',
                    'description' => 'Refinanciación de deuda mayor al 12/2017 solicitado por MP',
                    'installments' => 5,
                    'charge' => $collectors[$collectorId]->administrative_expenses,
                    'state' => Sale::INITIATED,
                    'amount' => $amount,
                ]);
            }
        }

        print('Exito...');
        return;
    }
}
