<?php

namespace App;

use App\Repositories\SaleRepository;

class Sale extends SaleRepository
{
    public $errors = [];

    protected $fillable = [
        'amount',
        'charge',
        'payer_id',
        'collector_id',
        'installments',
        'sale_mode',
        'description',
        'state',
        'first_due_date',
        'period',
    ];

    protected $dates = [
        'first_due_date',
    ];

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
}
