<?php

namespace App\Providers;

use App\Repositories\Articles\ArticleRepository;
use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Repositories\Auth\PasswordRepository;
use App\Repositories\Auth\PasswordRepositoryInterface;
use App\Repositories\Auth\RegisterRepository;
use App\Repositories\Auth\RegisterRepositoryInterface;
use App\Repositories\Categories\CategoryRepository;
use App\Repositories\Categories\CategoryRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(
            RegisterRepositoryInterface::class,
            RegisterRepository::class
        );
        $this->app->bind(
            PasswordRepositoryInterface::class,
            PasswordRepository::class
        );
        $this->app->bind(
            ArticleRepositoryInterface::class,
            ArticleRepository::class
        );
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );
    }
}
