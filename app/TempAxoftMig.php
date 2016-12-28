<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempAxoftMig extends Model
{
    protected $table = 'temp_axoft_mig';


    protected $fillable = [
        'fecha',
        'process',
        'user_id',
        'cod_cuenta',
        'cod_comprobante',
        'comprobante',
        'leyenda',
        'debe',
        'haber',
    ];
}
