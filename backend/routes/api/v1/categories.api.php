<?php

use App\Http\Controllers\Api\V1\Blog\CategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('categories/')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [CategoryController::class, 'store'])
            ->name('store');
        Route::put('/{category}', [CategoryController::class, 'update'])
            ->name('update');
    });
});
