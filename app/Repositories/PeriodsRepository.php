<?php
namespace App\Repositories;

use App\Periods;
use App\Services\BusinessCore;
use App\Services\DoesNotExistOpenPeriodException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

abstract class PeriodsRepository extends Model
{
    use UuidForKey;
    public static function getPeriod($date = 'now')
    {
        $dateTime = ($date instanceof \DateTime) ? clone $date : new \DateTime($date);

        return $dateTime->format(BusinessCore::PERIOD_FORMAT);
    }

    /**
     * @return Periods
     */
    public static function getCurrentPeriod()
    {
        return Periods::firstOrFail()->whereNull('closed_at')
            ->whereNull('operator_id_closed')->first();
    }

    public static function getDueDate($period)
    {
        return;// new \DateTime('now');
    }

    public function close()
    {

        $period = $this->getCurrentPeriod()->uid;
        $this->update([
           'closed_at' => new \DateTime('now'),
           'operator_id_closed' => Auth::user()->id,
        ]);
        static::create([
           'uid' => BusinessCore::nextPeriod($period),
           'operator_id_opened' => Auth::user()->id,
        ]);
    }
}