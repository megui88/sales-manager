<?php

namespace App;

use App\Contract\CustomizeQuery;
use App\Contract\ModelPagination as Pagination;
use App\Services\BusinessCore;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements CustomizeQuery, Pagination
{
    use UuidForKey;
    use ModelPagination;
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
        'state',
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
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    static public function buildMember($data)
    {
        $user = new static;
        $user->builder($data);
        $user->role = BusinessCore::MEMBER_ROLE;
        $user->save();
    }

    static public function buildVendor($data)
    {
        $user = new static;
        $user->builder($data);
        $user->role = BusinessCore::VENDOR_ROLE;
        $user->save();
    }

    public function builder($data)
    {
        $this->credit_max =  BusinessCore::CREDIT_MAX;

        foreach ($data as $attribute => $value){
            $this->$attribute = $value;
        }

        if(empty($this->email)) {
            $this->password = Hash::make(str_random(8));
        }

        return $this;

    }

    public function getColumn()
    {
        return 'name';
    }

    public function getOperator()
    {
        return 'like';
    }
}
