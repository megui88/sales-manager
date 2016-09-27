<?php

namespace App;

use App\Repositories\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use UuidForKey;
    protected $fillable = [
        'name',
        'quote',
        'description',
    ];
}
