<?php

namespace App\Http\Services\Api\V1\Articles;

use App\Http\Requests\Api\V1\Articles\StoreArticleRequest;
use App\Models\Article;
use App\Repositories\Articles\ArticleRepositoryInterface;

class StoreArticleService
{
    public function __construct(
        private StoreArticleRequest $request,
        private ArticleRepositoryInterface $repository
    ) {}

    public function store(): Article
    {
        return $this->repository->create(
            title: $this->request->title,
            slug: $this->request->slug,
            content: $this->request->get('content'),
            writerId: $this->request->user()->getKey(),
            categoryId: $this->request->category_id,
        );
    }
}
