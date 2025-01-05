<?php

use App\Http\Controllers\Api\V1\Blog\ArticleController;
use Illuminate\Support\Facades\Route;

Route::prefix('articles/')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])
        ->name('list');
});
