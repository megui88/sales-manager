<?php
namespace App\Repositories;

use App\Services\BusinessCore;
use Illuminate\Database\Eloquent\Model;

abstract class PeriodsRepository extends Model
{
    use UuidForKey;
    public static function getPeriod($date = 'now')
    {
        $dateTime = ($date instanceof \DateTime) ? clone $date : new \DateTime($date);

        return $dateTime->format(BusinessCore::PERIOD_FORMAT);
    }

    public static function getCurrentPeriod()
    {
        return date('Ym', strtotime('now'));
    }

    public static function getDueDate($period)
    {
        return;// new \DateTime('now');
    }
}