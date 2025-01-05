<?php

namespace App\Http\Services\Api\V1\Articles;

use App\Http\Requests\Api\V1\Articles\StoreArticleRequest;
use App\Http\Requests\Api\V1\Articles\UpdateArticleRequest;
use App\Models\Article;
use App\Repositories\Articles\ArticleRepositoryInterface;

class UpdateArticleService
{
    public function __construct(
        private UpdateArticleRequest $request,
        private ArticleRepositoryInterface $repository
    ) {}

    public function update(Article $article): Article
    {
        return $this->repository->update(
            article: $article,
            title: $this->request->title,
            slug: $this->request->slug,
            content: $this->request->get('content'),
            categoryId: $this->request->category_id,
        );
    }
}
