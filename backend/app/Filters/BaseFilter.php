<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class BaseFilter implements FilterInterface
{
    protected Builder $builder;

    /** @var array<string> */
    protected array $filters = [];

    public function __construct(private Request $request)
    {
    }

    public function apply(Builder $builder): void
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if ($value === null) {
                continue;
            }

            $method = Str::camel($filter);

            $this->$method($value);
        }
    }

    private function getFilters(): array
    {
        return $this->request->only($this->filters);
    }
}
