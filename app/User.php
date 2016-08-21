<?php

namespace App;

use App\Repositories\UserRepository;

class User extends UserRepository
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'email',
        'password',
        'last_name',
        'document',
        'address',
        'location',
        'phone',
        'cellphone',
        'internal_phone',
        'credit_max',
        'birth_date',
        'group_id',
        'debit_automatic',
        'cuil_cuit',
        'fantasy_name',
        'business_name',
        'category_id',
        'web',
        'stand',
        'discharge_date',
        'leaving_date',
        'cbu',
        'state',
        'role',
        'enable',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
