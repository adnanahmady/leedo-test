<?php

namespace App\Http\Controllers\Api\V1\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Blog\CategoryResource;
use App\Http\Services\Api\V1\Categories\StoreCategoryService;
use App\Http\Services\Api\V1\Categories\UpdateCategoryService;
use App\Models\Category;

class CategoryController extends Controller
{
    public function store(StoreCategoryService $service): CategoryResource
    {
        return new CategoryResource($service->store());
    }

    public function update(
        UpdateCategoryService $service,
        Category $category
    ): CategoryResource {
        return new CategoryResource($service->update($category));
    }
}
