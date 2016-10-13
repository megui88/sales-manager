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
        'administrative_expenses',
        'company_id',
        'headquarters_id',
    ];

    protected $dates = [
        'birth_date',
        'discharge_date',
        'leaving_date',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function headquarters()
    {
        return $this->belongsTo(Headquarters::class, 'headquarters_id', 'id');
    }
}
