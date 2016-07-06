<?php

namespace App\Contract;

use App\Comment;

interface Commentable
{
    /**
     * @return Comment
     */
    public function comment();
}