<?php

namespace App\Contract;

interface States
{
    const INITIATED = 'initiated'; //Default state
    const OPENED = 'opened';
    const REPROCESSED = 'reprocess';
    const PROCESSED = 'processed';
    const PENDING = 'pending';
    const WAITING = 'waiting';
    const ANNULLED = 'annulled';
    const STOPPED = 'stopped';
    const CLOSED = 'closed';
    const COMPLETED = 'completed';

    /**
     * @return string
     */
    public function getState();
}
