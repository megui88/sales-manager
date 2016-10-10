<?php

namespace App;

use App\Repositories\UuidForKey;
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

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }
}
