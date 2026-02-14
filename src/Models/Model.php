<?php

declare(strict_types=1);

namespace App\Models;

abstract class Model
{
    /** @var string[] */
    protected array $fillable = [];

    public function onlyFillable(array $attributes): array
    {
        return array_intersect_key($attributes, array_flip($this->fillable));
    }
}