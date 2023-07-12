<?php

namespace Modules\Authentication\Support\Traits\Concerns\Model;

use Illuminate\Support\Str;

trait HasUUID
{
    protected static function bootHasUUID()
    {
        static::creating(function($model) {
            $model->setAttribute('uuid', Str::uuid()->toString());
        });
    }
}
