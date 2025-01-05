<?php

namespace App\Http\Services\Api\V1\Categories;

use App\Http\Requests\Api\V1\Categories\StoreCategoryRequest;
use App\Models\Article;
use App\Models\Category;
use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Repositories\Categories\CategoryRepositoryInterface;

class StoreCategoryService
{
    public function __construct(
        private StoreCategoryRequest $request,
        private CategoryRepositoryInterface $repository
    ) {}

    public function store(): Category
    {
        return $this->repository->create(
            name: $this->request->name,
            slug: $this->request->slug,
            ownerId: $this->request->user()->getKey(),
        );
    }
}
