<?php

namespace App\Services;

class BusinessCore
{
    const CREDIT_MAX = 40000;
    const AGENT_ROLE = 9;
    const VENDOR_ROLE = 1;
    const MEMBER_ROLE = 2;
    const PERIOD_FORMAT = 'Ym';
    const PERIOD_EXP_REG = '/^(\d{4})(\d{2})+$/i';
    const PERIOD_EXP_REG_REMP = '${1}-$2-01';
    const DUE_DATE_FORMAT = 'Y-m-d';
    const DECIMALS_PLACES = 2;
    const PRINT_DECIMALS = 2;
    const PRINT_DEC_POINT = '.';
    const PRINT_THOUSANDS_SEP = ',';

    /**
     * @param $number
     * @return string|float
     * @throws BusinessException
     */
    public function printAmount($number)
    {
        if (!is_numeric($number)) {
            throw new BusinessException('try to format a nonnumeric');
        }

        return number_format((float) $number, self::PRINT_DECIMALS, self::PRINT_DEC_POINT, self::PRINT_THOUSANDS_SEP);
    }

    /**
     * @param $number
     * @return float
     * @throws BusinessException
     */
    public function amountFormat($number)
    {
        if (!is_numeric($number)) {
            throw new BusinessException('try to format a nonnumeric');
        }

        return (float) number_format($number, self::DECIMALS_PLACES,  '.', '');
    }

    /**
     * @param string $period
     * @return bool
     */
    public function isValidPeriodFormat($period = null)
    {
        if (is_null($period)) {
            return false;
        }

        try {
            if (!preg_match(self::PERIOD_EXP_REG, $period)) {
                return false;
            }
            $tmp_date_string = preg_replace(self::PERIOD_EXP_REG, self::PERIOD_EXP_REG_REMP, $period);
            $date = new \DateTime($tmp_date_string);
        } catch (\Exception $e) {
            return false;
        }

        return ($date->format('Y') > 1990) && checkdate($date->format('m'), $date->format('d'), $date->format('Y'));
    }

    /**
     * @param string $date
     * @param int $months
     * @return string
     */
    public function dateToPeriodFormat($date = null, $months = 0)
    {
        if (is_null($date)) {
            return;
        }
        $dateTime = ($date instanceof \DateTime) ? clone $date : new \DateTime($date);

        return (string) $dateTime->modify("last day of +$months month")->format(self::PERIOD_FORMAT);
    }

    /**
     * @param string $date
     * @param int $months
     * @return string
     */
    public function dueDateFormat($date = null, $months = 0)
    {
        if (is_null($date)) {
            return;
        }
        $dateTime = ($date instanceof \DateTime) ? clone $date : new \DateTime($date);

        return (string) $dateTime->modify("last day of +$months month")->format(self::DUE_DATE_FORMAT);
    }

    /**
     * @param $first_period
     * @param $totalInstallment
     * @param null $installment
     * @return array|string
     * @throws BusinessException
     */
    public function calculateFuturePeriod($first_period, $totalInstallment, $installment = null)
    {
        $periods = [];
        $tmp_date_string = preg_replace(self::PERIOD_EXP_REG, self::PERIOD_EXP_REG_REMP, $first_period);
        for ($i = 0; $i < $totalInstallment; ++$i) {
            $periods [$i + 1] = $this->dateToPeriodFormat($tmp_date_string, $i);
        }
        if (!is_null($installment) && !empty($periods[$installment])) {
            return $periods[$installment];
        } elseif (!is_null($installment) && empty($periods[$installment])) {
            throw new BusinessException('The period requested does not exist');
        }

        return $periods;
    }

    /**
     * @param float $amount
     * @param float $recharge
     * @return float
     */
    public function subtractCharge($amount, $recharge)
    {
        return $this->amountFormat($amount - $this->calculateIncome($amount, $recharge));
    }

    /**
     * @param float $amount
     * @param float $recharge
     * @return float
     * @throws BusinessException
     */
    public function calculateIncome($amount, $recharge)
    {
        if (!is_numeric($amount) || !is_numeric($recharge)) {
            throw new BusinessException('try to format a nonnumeric');
        }

        return $this->amountFormat($amount * $recharge / 100);
    }

    /**
     * @param $first_due_date
     * @param $totalInstallment
     * @param null $installment
     * @return array|string
     * @throws BusinessException
     */
    public function calculateFutureDueDate($first_due_date, $totalInstallment, $installment = null)
    {
        $periods = [];
        for ($i = 0; $i < $totalInstallment; ++$i) {
            $periods [$i + 1] = $this->dueDateFormat($first_due_date, $i);
        }
        if (!is_null($installment) && !empty($periods[$installment])) {
            return $periods[$installment];
        } elseif (!is_null($installment) && empty($periods[$installment])) {
            throw new BusinessException('The due date requested does not exist');
        }

        return $periods;
    }

    /**
     * @param float $amount
     * @param int $totalInstallment
     * @param null|int $installment
     * @return array|int
     * @throws BusinessException
     */
    public function calculateTheValueOfTheAmountOfEachInstallment($amount, $totalInstallment, $installment = null)
    {
        $quote = $amount / $totalInstallment;
        $quotes = [];
        for ($i = 1; $i <= $totalInstallment; ++$i) {
            $quotes [$i] = $this->amountFormat($quote);
        }

        $partial = array_sum($quotes);
        if ($partial < $amount) {
            $quotes[count($quotes)] +=  $amount - $partial;
        }

        if ($partial > $amount) {
            $quotes[count($quotes)] -=  $partial - $amount;
        }

        if (!is_null($installment) && !empty($quotes[$installment])) {
            return $quotes[$installment];
        } elseif (!is_null($installment) && empty($quotes[$installment])) {
            throw new BusinessException('The quote requested does not exist');
        }

        return $quotes;
    }
}
