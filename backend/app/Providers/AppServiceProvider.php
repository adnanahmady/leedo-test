<?php

namespace App\Providers;

use App\Repositories\Auth\RegisterRepository;
use App\Repositories\Auth\RegisterRepositoryInterface;
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
    }
}
