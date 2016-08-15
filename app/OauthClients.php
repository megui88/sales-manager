<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OauthClients extends Model
{
    public $incrementing = false;

    public $table = 'oauth_clients';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'secret', 'name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'secret',
    ];
}
