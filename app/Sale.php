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
        'concept_id',
        'description',
        'state',
        'first_due_date',
        'period',
        'migrate_id',
    ];

    protected $dates = [
        'first_due_date',
    ];
}
