<?php

namespace App\Repositories\Articles;

use App\Models\Article;

interface ArticleRepositoryInterface
{
    public function create(
        string $title,
        string $slug,
        string $content,
        int $writerId,
        int $categoryId,
    ): Article;
}