<?php

namespace App\Events;

use App\Due;
use App\Sale;
use Illuminate\Queue\SerializesModels;

class NewSaleEvent extends Event
{
    use SerializesModels;

    private $sale;

    public function __construct(Sale $sale)
    {
        $this->sale = $sale;
    }

    /**
     * @return Sale
     */
    public function getSale()
    {
        return $this->sale;
    }
}
