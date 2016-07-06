<?php

namespace App\Contract;

interface States
{
    const INITIATED = 'initiated'; //Default state
    const OPENED    = 'opened';
    const PROCESSED = 'processed';
    const WAITING   = 'waiting';
    const ANNULLED  = 'annulled';
    const STOPPED   = 'stopped';
    const CLOSED    = 'closed';
    const COMPLETED = 'completed';

    /**
     * @return string
     */
    public function getState();

}