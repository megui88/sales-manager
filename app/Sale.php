<?php

namespace App;

use App\Contract\States;
use App\Contract\Transactional;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Sale extends Model implements Transactional, States
{

    protected $fillable = [
        'amount',
        'charge',
        'payer_id',
        'collector_id',
        'payer_id',
        'installments',
        'state',
        'first_date_due',
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function($entity){
            $entity->state = self::INITIATED;
        });

        self::created(function($sale){
            for($x = 0 ; $x < $sale->installments ; ++$x){
                $date_due = (new \DateTime($sale->first_date_due->format(DATE_ATOM)))->modify("+$x month");
                $due = Due::create([
                    'sale_id' => $sale->id,
                    'amount_of_quota' => ($sale->amount / $sale->installments),   //@todo move to service
                    'number_of_quota' =>  ($x + 1),
                    'payer_id' => $sale->payer_id,
                    'date_due' => $date_due,
                    'state' => self::INITIATED,
                ]);
                $due->save();
            }
        });
    }

    public function getState()
    {
        return $this->getAttribute('state');
    }

    public function transaction()
    {
        return $this->morphMany('App\Transaction', 'transactional');
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
        return $this->hasMany('App\Due');
    }
}
