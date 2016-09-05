<?php

namespace App\Contract;

interface ModelPagination
{
    function canUseInPaginate($key);
}
