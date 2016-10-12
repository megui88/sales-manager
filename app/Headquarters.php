<?php

namespace App;

use App\Repositories\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class Headquarters extends Model
{
    use UuidForKey;
    protected $fillable = [
        'name',
        'location',
        'description',
    ];
}
