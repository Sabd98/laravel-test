<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::orderedUuid()->toString();
            }
        });
    }

    public function getKeyType()
    {
        return 'string';
    }

    public function getIncrementing()
    {
        return false;
    }
}
