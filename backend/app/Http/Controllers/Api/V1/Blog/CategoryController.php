<?php

namespace App\Http\Controllers\Api\V1\Blog;

use App\Http\Controllers\Controller;
use app\Http\Requests\Api\V1\Articles\UpdateArticleRequest;
use App\Http\Resources\Api\V1\Blog\CategoryResource;
use App\Http\Services\Api\V1\Categories\StoreCategoryService;
use App\Models\Article;

class CategoryController extends Controller
{
    public function store(StoreCategoryService $service): CategoryResource
    {
        return new CategoryResource($service->store());
    }
}
