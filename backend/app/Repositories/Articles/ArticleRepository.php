<?php

namespace App\Repositories\Articles;

use App\Models\Article;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function create(
        string $title,
        string $slug,
        string $content,
        int $writerId,
        int $categoryId,
    ): Article {
        return Article::create([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'writer_id' => $writerId,
            'category_id' => $categoryId,
        ]);
    }
}
