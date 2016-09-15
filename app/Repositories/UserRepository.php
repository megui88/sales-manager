<?php
namespace App\Repositories;

use App\Contract\CustomizeQuery;
use App\Contract\ModelPagination as Pagination;
use App\Services\BusinessCore;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;

abstract class UserRepository extends Authenticatable implements CustomizeQuery, Pagination
{
    use UuidForKey;
    use ModelPagination;

    static public function buildMember($data)
    {
        $user = new static;
        $user->builder($data);
        $user->role = BusinessCore::MEMBER_ROLE;
        $user->save();

        return $user;
    }

    static public function buildVendor($data)
    {
        $user = new static;
        $user->builder($data);
        $user->role = BusinessCore::VENDOR_ROLE;
        $user->save();

        return $user;
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

    /**
     * @param Builder $query
     * @param string $value
     * @param null $filters
     * @return Builder $query
     */
    public function customQuery(Builder $query, $value = '', $filters = null)
    {
        return $query->where(function($q) use ($value) {
            /** @var Builder $q */
            $q->where('name', 'like', $value . '%')
                ->orWhere('last_name', 'like', $value . '%')
                ->orWhere('code', 'like', $value . '%')
                ->orWhere('email', 'like', '%' . $value . '%');
        });
    }

    public function disEnrolled()
    {
        $this->enable = false;
        $this->state = BusinessCore::MEMBER_DISENROLLED;
        $this->leaving_date = new \DateTime('now');
        $this->save();
    }

    public function fullName()
    {
        return $this->last_name . ' ' . $this->name;
    }
}