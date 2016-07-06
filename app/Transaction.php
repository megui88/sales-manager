<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'client_id',
        'office_id',
        'operator_id',
        'supervisor_id',
        'payer_id',
        'collector_id',
        'state',
    ];

    public function transactional()
    {
        return $this->morphTo();
    }
}
