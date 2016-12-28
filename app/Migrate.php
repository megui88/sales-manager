<?php

namespace App;

use App\Repositories\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class Migrate extends Model
{
    const PHARMACY_TYPE = 'pharmacy';
    const BULK_TYPE = 'bulk';
    const AXOFT_TYPE = 'axoft';

    use UuidForKey;
    protected $fillable = [
        'name',
        'type',
        'checksum',
        'description',
        'status',
        'errors',
    ];

    protected $casts = [
        'errors' => 'array',
    ];
}
