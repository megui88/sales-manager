<?php

namespace App;

use App\Repositories\PeriodsRepository;

class Periods extends PeriodsRepository
{
    protected $fillable = [
        'uid',
        'due_date',
        'closed_at',
        'operator_id_opened',
        'operator_id_closed',
    ];
    protected $dates = [
        'due_date',
        'closed_at',
    ];

    public function __toString()
    {
        return $this->uid;
    }
}
