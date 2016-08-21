<?php

namespace App\Repositories;


trait ModelPagination
{
    public function canUseInPaginate($key)
    {
        return in_array($key, $this->fillable) && ! in_array($key, $this->hidden);
    }
}