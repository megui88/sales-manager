<?php

namespace App;

use App\Repositories\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    use UuidForKey;
    protected $fillable = [
        'name',
        'description',
    ];
}
