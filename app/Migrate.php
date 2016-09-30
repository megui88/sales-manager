<?php

namespace App;

use App\Repositories\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class Migrate extends Model
{
    use UuidForKey;
    protected $fillable = [
        'name',
        'checksum',
        'description',
        'status',
        'errors',
    ];

    protected $casts = [
        'errors' => 'array',
    ];
}
