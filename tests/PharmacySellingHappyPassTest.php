<?php

use App\Sale;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PharmacySellingHappyPassTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

    }

    public function getData()
    {
        $operator = factory(\App\User::class)->create([
            'role' => \App\Services\BusinessCore::EMPLOYEE_ROLE
        ]);
        $payer = factory(\App\User::class)->create([
            'administrative_expenses' => 0,
            'role' => \App\Services\BusinessCore::MEMBER_ROLE
        ]);
        $period = factory(\App\Periods::class)->create();
        \Illuminate\Support\Facades\Auth::login($operator);

        return [
            'amount' => rand(1000, 10),
            'installments' => rand(3,12),
            'charge' => 100,
            'payer_id' => $payer->id,
            'collector_id' => 0,
            'sale_mode' => Sale::PHARMACY_SELLING,
            'period' => $period->uid,
            'first_due_date' => $period->due_date,
        ];
    }

    public function testPharmacySelling()
    {
        $data = $this->getData();
        $data['period'] = null;
        $this->assertTrue($this->saleHappyPass($data));
    }


    /**
     * A basic test happy route pass of current account.
     *
     * @return bool
     * @throws \Exception
     */
    public function saleHappyPass($data)
    {
        $business = new \App\Services\BusinessCore();
        $totalDue = 0;
        $totalCollect = 0;

        //When
        $sale = Sale::create($data);
        if (!empty($sale->errors)) {
            throw new \Exception(implode(' ', $sale->errors));
        }

        //Then
        $this->seeInDatabase('sales', ['id' => $sale->id]);
        $this->assertEquals(1, $sale->transaction->count());

        $transaction = \App\Transaction::where('transactional_type', 'sales')
            ->where('transactional_id', $sale->id)->first();

        $this->assertNotEmpty($transaction);
        $this->assertEquals(1, $transaction->client_id);
        $this->assertEquals(2, $transaction->office_id);
        $this->assertEquals(\Illuminate\Support\Facades\Auth::user()->id, $transaction->operator_id);
        $this->assertEquals(null, $transaction->supervisor_id);
        $this->assertEquals($data['installments'], $sale->installments);
        $this->assertEquals($data['amount'], $sale->amount);
        $this->assertEquals($data['charge'], $sale->charge);
        $this->assertEquals($data['payer_id'], $sale->payer_id);
        $this->assertEquals($data['collector_id'], $sale->collector_id);
        $this->assertEquals($sale->transaction[0]->id, $transaction->id);

        //Due
        //When
        $dues = $sale->dues;

        //Then
        if($data['installments'] != $dues->count()){
            dd($data['installments'], $dues->count());
        }
        $this->assertEquals($data['installments'], $dues->count());

        foreach ($dues as $index => $due) {
            $totalDue += $due->amount_of_quota;
            $this->assertEquals($index + 1, $due->number_of_quota);
            if (!is_null($data['first_due_date'])) {
                $due_date = clone $data['first_due_date'];
                $evaluated = $due_date->modify("last day of +$index month");
                $this->assertEquals($evaluated->format('Y-m-d'), $due->due_date);
                $this->assertEquals($business->dateToPeriodFormat($evaluated), $due->period);
                $this->assertEquals(
                    $business->calculateFutureDueDate($data['first_due_date'], $data['installments'], ($index + 1)),
                    $due->due_date
                );
            }
            $this->assertNotEmpty($due->period);
            $this->assertEquals(
                $business->calculateFuturePeriod($sale->period, $data['installments'], ($index + 1)),
                $due->period
            );
            $this->assertEquals($data['payer_id'], $due->payer_id);
            $this->assertEquals(
                $business->calculateTheValueOfTheAmountOfEachInstallment($data['amount'], $data['installments'], ($index + 1)),
                $due->amount_of_quota
            );
            unset($due_date);
        }

        //Incomes
        //When
        $incomes = $sale->incomes;

        //Then
        $this->assertEquals($data['installments'], $incomes->count());
        $amountIncome = $business->calculateIncome($data['amount'], $data['charge']);

        foreach ($incomes as $index => $income) {
            $totalCollect += $income->amount_of_quota;
            if (!is_null($data['first_due_date'])) {
                $due_date = clone $data['first_due_date'];
                $evaluated = $due_date->modify("last day of +$index month");
                $this->assertEquals($evaluated->format('Y-m-d'), $income->due_date);
                $this->assertEquals($business->dateToPeriodFormat($evaluated), $income->period);
                $this->assertEquals(
                    $business->calculateFutureDueDate($data['first_due_date'], $data['installments'], ($index + 1)
                    ), $income->due_date);
            }

            $this->assertNotEmpty($income->period);
            $this->assertEquals(
                $business->calculateFuturePeriod($sale->period, $data['installments'], ($index + 1)),
                $income->period
            );
            $this->assertEquals($data['payer_id'], $income->payer_id);
            $this->assertEquals($data['collector_id'], $income->collector_id);
            $this->assertEquals(
                $business->calculateTheValueOfTheAmountOfEachInstallment($amountIncome, $data['installments'], ($index + 1)),
                $income->amount_of_quota
            );
        }

        $this->assertEquals($totalDue, $totalCollect);
        $this->assertEquals($data['amount'], $totalDue);

        return true;
    }
}
