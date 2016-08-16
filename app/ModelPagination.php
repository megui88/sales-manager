<?php

namespace App;


trait ModelPagination
{
    public function canUseInPaginate($key)
    {
        return in_array($key, $this->fillable) && ! in_array($key, $this->hidden);
    }
}