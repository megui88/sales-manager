<?php

namespace App;

use App\Services\BusinessCore;
use Illuminate\Database\Eloquent\Model;

class Periods extends Model
{
    use UuidForKey;
    protected $fillable = [
        'uid',
        'due_date',
        'closed_at',
        'operator_id_opened',
        'operator_id_closed',
    ];

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
