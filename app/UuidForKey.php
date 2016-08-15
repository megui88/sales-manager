<?php

namespace App;

use Ramsey\Uuid\Uuid;

trait UuidForKey
{
    /**
     * Boot the Uuid trait for the model.
     */
    public static function bootUuidForKey()
    {
        static::creating(function ($model) {
            $model->incrementing = false;
            $model->{$model->getKeyName()} = (string) Uuid::uuid1();
        });
    }

    /**
     * Get the casts array.
     *
     * @return array
     */
    public function getCasts()
    {
        return $this->casts;
    }
}