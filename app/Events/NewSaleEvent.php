<?php

namespace App\Events;

use App\Sale;
use Illuminate\Queue\SerializesModels;

class NewSaleEvent extends Event
{
    const TYPE = 'new';

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
