<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accredit extends Model
{
    use UuidForKey;
    protected $fillable = [
        'sale_id',
        'amount_of_quota',
        'number_of_quota',
        'collector_id',
        'due_date',
        'period',
        'state',
    ];
}
