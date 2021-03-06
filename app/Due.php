<?php

namespace App;

use App\Repositories\UuidForKey;
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

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

}
