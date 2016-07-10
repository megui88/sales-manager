<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incomes extends Model
{
    protected $fillable = [
        'sale_id',
        'amount_of_quota',
        'number_of_quota',
        'payer_id',
        'collector_id',
        'due_date',
        'period',
        'state',
    ];
}
