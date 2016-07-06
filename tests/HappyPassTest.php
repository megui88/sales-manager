<?php

use App\Sale;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HappyPassTest extends TestCase
{
    #use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testTransactionCreate()
    {
        $installments = 3;
        $amount = 10.05;
        $charge = 5;
        $payer_id = 4;
        $collector_id = 5;
        $first_date_due = new \DateTime();

        //When
        $sale = Sale::create([
            'amount' => $amount,
            'installments' => $installments,
            'charge' => $charge,
            'payer_id' => $payer_id,
            'collector_id' => $collector_id,
            'first_date_due' => $first_date_due,
        ]);
        $sale->save();

        //Then
        $transaction = \App\Transaction::where('transactional_type', "sales")
            ->where('transactional_id', $sale->id)->first();

        $this->assertNotEmpty($transaction);
        $this->assertEquals(1, $transaction->client_id);
        $this->assertEquals(2, $transaction->office_id);
        $this->assertEquals(3, $transaction->operator_id);
        $this->assertEquals(null, $transaction->supervisor_id);
        $this->assertEquals($installments, $sale->installments);
        $this->assertEquals($amount, $sale->amount);
        $this->assertEquals($charge, $sale->charge);
        $this->assertEquals($payer_id, $sale->payer_id);
        $this->assertEquals($collector_id, $sale->collector_id);
        $this->assertEquals($sale->transaction[0]->id, $transaction->id);
        $this->assertNotEmpty($sale->collector_id);

        //Due

        //When
        $dues = $sale->dues();

        //Then
        $this->assertEquals($installments, $dues->count());
        foreach ($dues as $index => $due){
            $date_due = new \DateTime($first_date_due);
            $evaluated = $date_due->modify("+$index month");
            $this->assertEquals($evaluated->format(DATE_ATOM), $due->date_due->format(DATE_ATOM));
        }
    }
}
