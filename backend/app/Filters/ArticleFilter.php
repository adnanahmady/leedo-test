<?php

namespace App\Filters;

class ArticleFilter extends BaseFilter
{
    protected array $filters = [
        'writer',
        'category',
        'content',
    ];

    protected function writer(string $name): void
    {
        $this->builder->whereHas(
            'writer',
            fn($q) => $q->where('name', 'like', "%$name%")
        );
    }

    protected function category(string $name): void
    {
        $this->builder->whereHas(
            'category',
            fn($q) => $q->where('name', 'like', "%$name%")
        );
    }

    protected function content(string $text): void
    {
        $this->builder->where('content', 'like', "%$text%");
    }
}
