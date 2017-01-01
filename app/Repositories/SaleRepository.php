<?php
namespace App\Repositories;

use App\Accredit;
use App\Concept;
use App\Contract\Channels;
use App\Contract\States;
use App\Contract\Transactional;
use App\Due;
use App\Events\NewSaleEvent;
use App\Incomes;
use App\Periods;
use App\Transaction;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

abstract class SaleRepository extends Model implements Transactional, States, Channels
{
    use ModelPagination;

    public static $required = [
        'amount',
        'charge',
        'payer_id',
        'collector_id',
        'installments',
        'sale_mode',
        'state',
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($entity) {

            $entity->state = self::INITIATED;
            if (empty($entity->period) && empty($entity->first_due_date)) {
                $entity->period = Periods::getCurrentPeriod()->uid;
            }
            if (empty($entity->period) && !empty($entity->first_due_date)) {
                $entity->period = Periods::getPeriod($entity->first_due_date);
            }

            if (!empty($entity->period) && empty($entity->first_due_date)) {
                $entity->first_due_date = Periods::getDueDate($entity->period);
            }

            foreach (self::$required as $key) {
                if (empty($entity->$key) && 0 !== $entity->$key) {
                    $entity->errors [] = "The attribute $key is required.";
                }
            }
            return empty($entity->errors);
        });

        self::created(function ($sale) {
            Event::fire(new NewSaleEvent($sale));
        });
    }

    public function getState()
    {
        return $this->getAttribute('state');
    }

    public function transaction()
    {
        return $this->morphMany(Transaction::class, 'transactional');
    }

    public function getCollectorId()
    {
        $this->getAttribute('collector_id');
    }

    public function getPayerId()
    {
        $this->getAttribute('payer_id');
    }

    public function dues()
    {
        return $this->hasMany(Due::class);
    }

    public function accredits()
    {
        return $this->hasMany(Accredit::class);
    }

    public function incomes()
    {
        return $this->hasMany(Incomes::class);
    }
    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id', 'id');
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id', 'id');
    }

    public function concept()
    {
        return $this->belongsTo(Concept::class, 'concept_id', 'id');
    }

    public function getMutualUser()
    {
        $user = new User();
        $user->id = 'no-id';
        $user->code = 'M7M';
        $user->name = 'Mutual';
        $user->last_name = '7 de Marzo';
        $user->fantasy_name = 'Mutual 7 de Marzo';
        $user->business_name = 'Mutual 7 de Marzo';
        $user->address = 'Av. Bicentenario 1503';
        $user->location = 'Merlo';
        $user->phone = '0220 489 3671';
        $user->dni = '30551304686';
        $user->cuil_cuit = '30551304686';
        $user->state = '30551304686';

        return $user;
    }
}