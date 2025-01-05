<?php

namespace App\Http\Controllers\Api\V1\Blog;

use App\Filters\ArticleFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\Api\V1\Blog\ArticlesCollection;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index(ArticleFilter $filter): ArticlesCollection
    {
        $articles = Article::filter($filter)->get();

        return new ArticlesCollection($articles);
    }
}
