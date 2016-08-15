<?php

namespace App\Contract;

use App\Transaction;

interface Transactional
{
    /**
     * @return Transaction
     */
    public function transaction();

    public function getPayerId();

    public function getCollectorId();
}
