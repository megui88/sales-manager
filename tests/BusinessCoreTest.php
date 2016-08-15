<?php

use App\Services\BusinessCore;
use App\Services\BusinessException;

class BusinessCoreTest extends TestCase
{
    /**
     * @var BusinessCore
     */
    private $business;
    public function setUp()
    {
        $this->business = new BusinessCore();
        parent::setUp();
    }

    public function testSubtractChargeAndCalculateIncome()
    {
        $this->assertEquals(95, $this->business->subtractCharge(100, 5));
        $this->assertEquals(95, $this->business->subtractCharge('100', 5));
        $this->assertEquals(95,  $this->business->subtractCharge('100', '5'));
        $this->assertEquals(95, $this->business->subtractCharge(100, '5'));
        $this->assertEquals(95, $this->business->subtractCharge('100.00', '5'));

        try {
            $this->assertEquals(95, $this->business->subtractCharge('100,00', '5'));
        } catch (BusinessException $e) {
            $this->assertEquals('try to format a nonnumeric', $e->getMessage());
        }

        try {
            $this->assertEquals(100, $this->business->subtractCharge('100|@½@|½', '|@½@|½5'));
        } catch (BusinessException $e) {
            $this->assertEquals('try to format a nonnumeric', $e->getMessage());
        }

        for ($x = 0; $x <= 100; ++$x) {
            $amountRandom = rand($x.'.01', 100000.59);
            $chargeRandom = rand(1, 10);
            $remaining = $this->business->subtractCharge($amountRandom, $chargeRandom);
            $income = $this->business->calculateIncome($amountRandom, $chargeRandom);
            $this->assertEquals($amountRandom - $income, $remaining);
        }
    }

    public function testPrintAmount()
    {
        $business = $this->business;
        $this->assertEquals('10.00', $business->printAmount(10));
        $this->assertEquals('1,000.00', $business->printAmount(1000));
        $this->assertEquals('10.00', $business->printAmount('10'));
        $this->assertEquals('1,000.00', $business->printAmount('1000'));
        $this->assertEquals('10.53', $business->printAmount(10.526));
        $this->assertEquals('10.53', $business->printAmount('10.526'));
        try {
            $business->printAmount('|@½|đß@đ');
        } catch (BusinessException $e) {
            $this->assertEquals('try to format a nonnumeric', $e->getMessage());
        }
    }

    public function testAmountFormat()
    {
        $business = $this->business;
        $this->assertEquals(10.00, $business->amountFormat(10));
        $this->assertEquals(1000.00, $business->amountFormat(1000));
        $this->assertEquals(10.00, $business->amountFormat('10'));
        $this->assertEquals(1000.00, $business->amountFormat('1000'));
        $this->assertEquals(10.53, $business->amountFormat(10.526));
        $this->assertEquals(10.53, $business->amountFormat('10.526'));
        try {
            $business->amountFormat('|@½|đß@đ');
        } catch (BusinessException $e) {
            $this->assertEquals('try to format a nonnumeric', $e->getMessage());
        }
    }

    public function testIsValidPeriodFormat()
    {
        $business = $this->business;
        $this->assertTrue($business->isValidPeriodFormat('201702'));
        $this->assertTrue($business->isValidPeriodFormat('207503'));
        $this->assertTrue($business->isValidPeriodFormat(201405));
        $this->assertFalse($business->isValidPeriodFormat(''));
        $this->assertFalse($business->isValidPeriodFormat('1!"%"!&"#%'));
        $this->assertFalse($business->isValidPeriodFormat('20164'));
        $this->assertFalse($business->isValidPeriodFormat('062104'));
        $this->assertFalse($business->isValidPeriodFormat('2015-01'));
    }

    public function testCalculateFuturePeriod()
    {
        $business = $this->business;
        $first_period = '201502';
        $periods = $business->calculateFuturePeriod($first_period, 12);
        $this->assertEquals(12, count($periods));
        foreach ($periods as $i => $period) {
            $this->assertEquals($period, $business->calculateFuturePeriod($first_period, 12, $i));
        }
        try {
            $business->calculateFuturePeriod($first_period, 12, 13);
        } catch (BusinessException $e) {
            $this->assertEquals('The period requested does not exist', $e->getMessage());
        }
    }

    public function testCalculateFutureDueDate()
    {
        $business = $this->business;
        $first_due_date = '2015-02-01';
        $periods = $business->calculateFutureDueDate($first_due_date, 12);
        $this->assertEquals(12, count($periods));
        foreach ($periods as $i => $period) {
            $this->assertEquals($period, $business->calculateFutureDueDate($first_due_date, 12, $i));
        }
        try {
            $business->calculateFutureDueDate($first_due_date, 12, 13);
        } catch (BusinessException $e) {
            $this->assertEquals('The due date requested does not exist', $e->getMessage());
        }
    }

    public function testMethodCalculateTheValueOfTheAmountOfEachInstallment()
    {
        $business = $this->business;

        try {
            $business->calculateTheValueOfTheAmountOfEachInstallment(10, 2, 3);
        } catch (BusinessException $e) {
            $this->assertEquals('The quote requested does not exist', $e->getMessage());
        }

        $quotes = $business->calculateTheValueOfTheAmountOfEachInstallment(10, 2);
        $this->assertEquals(10, array_sum($quotes));
        $this->assertEquals(5, $business->calculateTheValueOfTheAmountOfEachInstallment(10, 2, 1));
        $this->assertEquals(5, $business->calculateTheValueOfTheAmountOfEachInstallment(10, 2, 2));

        $quotes = $business->calculateTheValueOfTheAmountOfEachInstallment(7, 9);
        $this->assertEquals(7, array_sum($quotes));
        $this->assertEquals(0.78, $business->calculateTheValueOfTheAmountOfEachInstallment(7, 9, 1));
        $this->assertEquals(0.78, $business->calculateTheValueOfTheAmountOfEachInstallment(7, 9, 2));
        $this->assertEquals(0.76, $business->calculateTheValueOfTheAmountOfEachInstallment(7, 9, 9));

        $quotes = $business->calculateTheValueOfTheAmountOfEachInstallment(10, 3);
        $this->assertEquals(10, array_sum($quotes));
        $this->assertEquals(3.33, $business->calculateTheValueOfTheAmountOfEachInstallment(10, 3, 1));
        $this->assertEquals(3.33, $business->calculateTheValueOfTheAmountOfEachInstallment(10, 3, 2));
        $this->assertEquals(3.34, $business->calculateTheValueOfTheAmountOfEachInstallment(10, 3, 3));

        $quotes = $business->calculateTheValueOfTheAmountOfEachInstallment(1000, 6);
        $this->assertEquals(1000, array_sum($quotes));
        $this->assertEquals(166.67, $business->calculateTheValueOfTheAmountOfEachInstallment(1000, 6, 1));
        $this->assertEquals(166.67, $business->calculateTheValueOfTheAmountOfEachInstallment(1000, 6, 3));
        $this->assertEquals(166.65, $business->calculateTheValueOfTheAmountOfEachInstallment(1000, 6, 6));

        $quotes = $business->calculateTheValueOfTheAmountOfEachInstallment(900, 7);
        $this->assertEquals(900, array_sum($quotes));
        $this->assertEquals(128.57, $business->calculateTheValueOfTheAmountOfEachInstallment(900, 7, 1));
        $this->assertEquals(128.57, $business->calculateTheValueOfTheAmountOfEachInstallment(900, 7, 5));
        $this->assertEquals(128.58, $business->calculateTheValueOfTheAmountOfEachInstallment(900, 7, 7));

        $quotes = $business->calculateTheValueOfTheAmountOfEachInstallment(1000, 9);
        $this->assertEquals(1000, array_sum($quotes));
        $this->assertEquals(111.11, $business->calculateTheValueOfTheAmountOfEachInstallment(1000, 9, 1));
        $this->assertEquals(111.11, $business->calculateTheValueOfTheAmountOfEachInstallment(1000, 9, 5));
        $this->assertEquals(111.12, $business->calculateTheValueOfTheAmountOfEachInstallment(1000, 9, 9));
    }
}
