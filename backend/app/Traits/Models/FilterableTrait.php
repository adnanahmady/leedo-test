<?php

namespace App\Traits\Models;

use App\Filters\FilterInterface;
use Illuminate\Database\Eloquent\Builder;

trait FilterableTrait
{
    public function scopeFilter(
        Builder $builder,
        FilterInterface $filter
    ): Builder {
        $filter->apply($builder);

        return $builder;
    }
}
