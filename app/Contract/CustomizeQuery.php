<?php

namespace App\Contract;

use Illuminate\Database\Query\Builder;

interface CustomizeQuery
{
    function customQuery(Builder $query, $value = '', $filters = null);
}
