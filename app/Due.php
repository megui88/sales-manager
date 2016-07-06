<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Due extends Model
{
    protected $fillable = [
        'sale_id',
        'amount_of_quota',
        'number_of_quota',
        'payer_id',
        'date_due',
        'state',
    ];
}
