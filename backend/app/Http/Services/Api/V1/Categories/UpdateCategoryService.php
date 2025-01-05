<?php

namespace App\Http\Services\Api\V1\Categories;

use App\Http\Requests\Api\V1\Categories\UpdateCategoryRequest;
use App\Models\Category;
use App\Repositories\Categories\CategoryRepositoryInterface;

class UpdateCategoryService
{
    public function __construct(
        private UpdateCategoryRequest $request,
        private CategoryRepositoryInterface $repository
    ) {}

    public function update(Category $category): Category
    {
        return $this->repository->update(
            category: $category,
            name: $this->request->name,
            slug: $this->request->slug,
        );
    }
}
