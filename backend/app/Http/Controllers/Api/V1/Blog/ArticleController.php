<?php

namespace App\Http\Controllers\Api\V1\Blog;

use App\Filters\ArticleFilter;
use App\Http\Controllers\Controller;
use app\Http\Requests\Api\V1\Articles\StoreArticleRequest;
use app\Http\Requests\Api\V1\Articles\UpdateArticleRequest;
use App\Http\Resources\Api\V1\Blog\ArticleResource;
use App\Http\Resources\Api\V1\Blog\ArticlesCollection;
use App\Http\Services\Api\V1\Articles\StoreArticleService;
use App\Http\Services\Api\V1\Articles\UpdateArticleService;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index(ArticleFilter $filter): ArticlesCollection
    {
        $articles = Article::filter($filter)->get();

        return new ArticlesCollection($articles);
    }

    public function store(StoreArticleService $service): ArticleResource
    {
        return new ArticleResource($service->store());
    }

    public function update(
        UpdateArticleService $service,
        Article $article
    ): ArticleResource
    {
        return new ArticleResource($service->update($article));
    }
}
