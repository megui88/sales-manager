<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    use UuidForKey;
    protected $fillable = [
        'name',
    ];
}
