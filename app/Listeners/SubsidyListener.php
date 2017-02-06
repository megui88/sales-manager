<?php

namespace App\Listeners;

use App\Accredit;
use App\Events\NewSaleEvent;
use App\Incomes;
use App\Sale;
use App\Due;
use App\Services\BusinessCore;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SubsidyListener
{
    private $business;

    use InteractsWithQueue;

    public function __construct(BusinessCore $business)
    {
        $this->business = $business;
    }

    public function handle(NewSaleEvent $event)
    {
        $sale = $event->getSale();
        if ($sale->getAttribute('sale_mode') !== Sale::SUBSIDY) {
            return true;
        }

        if (!in_array($sale->getAttribute('state'),[Sale::INITIATED])) {
            return true;
        }

        $this->createDuesAndAccredits($sale);
    }

    private function createDuesAndAccredits(Sale $sale)
    {
        $dueAmountOfQuotes = $this->business->calculateTheValueOfTheAmountOfEachInstallment($sale->amount, $sale->installments);
        $accredit = $this->business->subtractCharge($sale->amount, $sale->charge);
        $accreditAmountOfQuotes = $this->business->calculateTheValueOfTheAmountOfEachInstallment($accredit, $sale->installments);
        $income = $this->business->calculateIncome($sale->amount, $sale->charge);
        $incomeAmountOfQuotes = $this->business->calculateTheValueOfTheAmountOfEachInstallment($income, $sale->installments);
        $periods = $this->business->calculateFuturePeriod($sale->period, $sale->installments);
        $due_dates = $this->business->calculateFutureDueDate($sale->first_due_date, $sale->installments);

        for ($quote = 1; $quote <= $sale->installments; ++$quote) {
            $data = [
                'sale_id' => $sale->id,
                'charge' => $sale->charge,
                'payer_id' => $sale->payer_id,
                'collector_id' => $sale->collector_id,
                'number_of_quota' => $quote,
                'amount_of_quota' => $dueAmountOfQuotes[$quote],
                'due_date' => $due_dates[$quote],
                'period' => $periods[$quote],
                'state' => Sale::INITIATED,
            ];
            Due::create($data);

            $data['amount_of_quota'] =  $accreditAmountOfQuotes[$quote];
            Accredit::create($data);

      //      $data['amount_of_quota'] =  -1 * $accreditAmountOfQuotes[$quote];
      //      Incomes::create($data);
        }
    }
}
