<?php

namespace App;

use App\Repositories\UuidForKey;
use Illuminate\Database\Eloquent\Model;

class CodeCompatibilizer extends Model
{
    protected $table = 'legajos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'legajo',
        'codigo',
        'nombre',
        'apellido',
    ];
}
