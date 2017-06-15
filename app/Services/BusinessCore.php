<?php

namespace App\Services;

use App\Periods;
use App\Sale;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class BusinessCore
{
    const CURRENT_MAX = 13000;
    const CREDIT_MAX = 40000;
    const AGENT_ROLE = 9;
    const VENDOR_ROLE = 'proveedor';
    const MEMBER_ROLE = 'socio';
    const EMPLOYEE_ROLE = 'empleado';
    const EMPLOYEE_ADMIN_ROLE = 'administrador';
    const PHARMACIST_ROLE = 'farmaceutico';
    const PERIOD_FORMAT = 'Ym';
    const PERIOD_EXP_REG = '/^(\d{4})(\d{2})+$/i';
    const PERIOD_EXP_REG_REMP = '${1}-$2-01';
    const DUE_DATE_FORMAT = 'Y-m-d';
    const DECIMALS_PLACES = 2;
    const PRINT_DECIMALS = 2;
    const PRINT_DEC_POINT = '.';
    const PRINT_THOUSANDS_SEP = ',';
    const MEMBER_AFFILIATE = 'afiliado';
    const MEMBER_DISENROLLED = 'desafiliado';

    public static function getPeriodAndFutures($total, $current = false)
    {
        try {
            $periods = [];
            $period = ($current) ? Periods::getCurrentPeriod() : '201607';

            $periods = array_merge($periods, self::calculateFuturePeriod($period, $total));
            return $periods;
        } catch (ModelNotFoundException $e) {
            throw new DoesNotExistOpenPeriodException('Does not exist open period');
        }
    }

    public static function nextPeriod($period)
    {
        if (!self::isValidPeriodFormat($period)) {
            throw new BusinessException('Period invalid: ' . $period);
        }
        $tmp_date_string = preg_replace(self::PERIOD_EXP_REG, self::PERIOD_EXP_REG_REMP, $period);
        return self::dateToPeriodFormat($tmp_date_string, 1);

    }


    public static function previousPeriod($period)
    {
        if (!self::isValidPeriodFormat($period)) {
            throw new BusinessException('Period invalid: ' . $period);
        }
        $tmp_date_string = preg_replace(self::PERIOD_EXP_REG, self::PERIOD_EXP_REG_REMP, $period);
        return self::dateToPeriodFormat($tmp_date_string, -1);

    }

    public static function AuthorizationPassword($password)
    {
        $results = User::where('role', '=', self::EMPLOYEE_ADMIN_ROLE)->get();
        foreach ($results as $result) {
            if (password_verify($password, $result->password)) {
                return true;
            };
        }

        return false;

    }

    /**
     * @param $number
     * @return string|float
     * @throws BusinessException
     */
    public static function printAmount($number)
    {
        if (!is_numeric($number)) {
            throw new BusinessException('try to format a nonnumeric');
        }

        return number_format((float)$number, self::PRINT_DECIMALS, self::PRINT_DEC_POINT, self::PRINT_THOUSANDS_SEP);
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

        return (float)number_format($number, self::DECIMALS_PLACES, '.', '');
    }

    /**
     * @param string $period
     * @return bool
     */
    public static function isValidPeriodFormat($period = null)
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
    public static function dateToPeriodFormat($date = null, $months = 0)
    {
        if (is_null($date)) {
            return;
        }
        $dateTime = ($date instanceof \DateTime) ? clone $date : new \DateTime($date);

        return (string)$dateTime->modify("last day of +$months month")->format(self::PERIOD_FORMAT);
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

        return (string)$dateTime->modify("last day of +$months month")->format(self::DUE_DATE_FORMAT);
    }

    /**
     * @param $first_period
     * @param $totalInstallment
     * @param null $installment
     * @return array|string
     * @throws BusinessException
     */
    public static function calculateFuturePeriod($first_period, $totalInstallment, $installment = null)
    {
        $periods = [];
        $tmp_date_string = preg_replace(self::PERIOD_EXP_REG, self::PERIOD_EXP_REG_REMP, $first_period);
        $periods [1] = $period = self::dateToPeriodFormat($tmp_date_string, 0);
        for ($i = 2; $i <= $totalInstallment; ++$i) {
            $periods [$i] = $period = self::nextPeriod($period);
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
        $result = ($recharge != 0) ? $amount * $recharge / 100 : 0;
        return $this->amountFormat($result);
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
            $quotes[count($quotes)] += $amount - $partial;
        }

        if ($partial > $amount) {
            $quotes[count($quotes)] -= $partial - $amount;
        }

        if (!is_null($installment) && isset($quotes[$installment])) {
            return $quotes[$installment];
        } elseif (!is_null($installment) && !isset($quotes[$installment])) {
            throw new BusinessException('The quote requested does not exist');
        }

        return $quotes;
    }

    public function getUserDuesByPeriod($userId, $period)
    {
        if (!self::isValidPeriodFormat($period)) {
            throw new BusinessException('Period invalid: ' . $period);
        }

        $current = DB::table('dues')
            ->select(DB::raw('sum(amount_of_quota) as amount'))
            ->where('payer_id', '=', $userId)
            ->where('period', '=', $period)
            ->whereNotIn('state', [Sale::PENDING, Sale::ANNULLED])
            ->get();
        return $current[0]->amount;
    }

    public function getUserDebtsFuture($userId)
    {
        $current = DB::table('dues')
            ->select(DB::raw('sum(amount_of_quota) as amount'))
            ->where('payer_id', '=', $userId)
            ->where('period', '>=', Periods::getCurrentPeriod()->uid)
            ->whereNotIn('state', [Sale::PENDING, Sale::ANNULLED])
            ->get();
        return $current[0]->amount;
    }
}
