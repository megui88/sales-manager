<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Due extends Model
{
    use UuidForKey;
    protected $fillable = [
        'sale_id',
        'amount_of_quota',
        'number_of_quota',
        'payer_id',
        'due_date',
        'period',
        'state',
    ];
}
