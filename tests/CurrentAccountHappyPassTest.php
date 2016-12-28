<?php

use App\Sale;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CurrentAccountHappyPassTest extends TestCase
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
        $collector = factory(\App\User::class)->create([
            'administrative_expenses' => rand(5,10),
            'role' => \App\Services\BusinessCore::VENDOR_ROLE
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
            'charge' => $collector->administrative_expenses,
            'payer_id' => $payer->id,
            'collector_id' => $collector->id,
            'sale_mode' => Sale::CURRENT_ACCOUNT,
            'period' => $period->uid,
            'first_due_date' => $period->due_date,
        ];
    }

    public function testSaleWithoutThePeriod()
    {
        $data = $this->getData();
        $data['period'] = null;
        $this->assertTrue($this->saleHappyPass($data));
    }

    public function testSaleWhenChargeIsZero()
    {
        $data = $this->getData();
        $data['charge'] = 0;
        $this->assertTrue($this->saleHappyPass($data));
    }

    public function testSaleLastDayOfMonth()
    {
        $data = $this->getData();
        $data['period'] = null;
        $init_date = '2015-12-31';
        for ($x = 1; $x <= 12; ++$x) {
            $due_date = new \DateTime($init_date);
            $data['first_due_date'] = $due_date->modify("last day of +$x month");
            $this->assertTrue($this->saleHappyPass($data));
        }
    }

    public function testSaleFirstDayOfMonth()
    {
        $data = $this->getData();
        $data['period'] = null;
        $init_date = '2015-12-01';
        for ($x = 1; $x <= 12; ++$x) {
            $due_date = new \DateTime($init_date);
            $data['first_due_date'] = $due_date->modify("first day of +$x month");
            $this->assertTrue($this->saleHappyPass($data));
        }
    }

    public function testSaleWithoutTheFirstDueDate()
    {
        $data = $this->getData();
        $data['first_due_date'] = null;
        $this->assertTrue($this->saleHappyPass($data));
    }

    public function testSaleWithoutThePeriodAndFirstDueDate()
    {
        $data = $this->getData();
        $data['period'] = null;
        $data['first_due_date'] = null;
        $this->assertTrue($this->saleHappyPass($data));
    }

    public function testSaleWithoutTheAmount()
    {
        try {
            $data = $this->getData();
            $data['amount'] = null;
            $this->saleHappyPass($data);
        } catch (\Exception $e) {
            $this->assertEquals('The attribute amount is required.', $e->getMessage());
        }
    }

    public function testSaleWithoutTheInstallments()
    {
        try {
            $data = $this->getData();
            $data['installments'] = null;
            $this->saleHappyPass($data);
        } catch (\Exception $e) {
            $this->assertEquals('The attribute installments is required.', $e->getMessage());
        }
    }

    public function testSaleWithoutThePayerId()
    {
        try {
            $data = $this->getData();
            $data['payer_id'] = null;
            $this->saleHappyPass($data);
        } catch (\Exception $e) {
            $this->assertEquals('The attribute payer_id is required.', $e->getMessage());
        }
    }

    public function testSaleWithoutTheCollectorId()
    {
        try {
            $data = $this->getData();
            $data['collector_id'] = null;
            $this->saleHappyPass($data);
        } catch (\Exception $e) {
            $this->assertEquals('The attribute collector_id is required.', $e->getMessage());
        }
    }

    public function testSaleWithoutTheSaleMode()
    {
        try {
            $data = $this->getData();
            $data['sale_mode'] = null;
            $this->saleHappyPass($data);
        } catch (\Exception $e) {
            $this->assertEquals('The attribute sale_mode is required.', $e->getMessage());
        }
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
        $this->assertNotEmpty($sale->collector_id);

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
        //Accredit
        //When
        $accredit = $sale->accredits;

        //Then
        $this->assertEquals($data['installments'], $accredit->count());
        $amountAccredit = $business->subtractCharge($data['amount'], $data['charge']);

        foreach ($accredit as $index => $accredit) {
            $totalCollect += $accredit->amount_of_quota;
            if (!is_null($data['first_due_date'])) {
                $due_date = clone $data['first_due_date'];
                $evaluated = $due_date->modify("last day of +$index month");
                $this->assertEquals($evaluated->format('Y-m-d'), $accredit->due_date);
                $this->assertEquals($business->dateToPeriodFormat($evaluated), $accredit->period);
                $this->assertEquals(
                    $business->calculateFutureDueDate($data['first_due_date'], $data['installments'], ($index + 1)
                    ), $accredit->due_date);
            }

            $this->assertNotEmpty($accredit->period);
            $this->assertEquals(
                $business->calculateFuturePeriod($sale->period, $data['installments'], ($index + 1)),
                $accredit->period
            );
            $this->assertEquals($data['collector_id'], $accredit->collector_id);
            $this->assertEquals(
                $business->calculateTheValueOfTheAmountOfEachInstallment($amountAccredit, $data['installments'], ($index + 1)),
                $accredit->amount_of_quota
            );
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
