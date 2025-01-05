<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\PasswordRecoveryController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::name('api.v1.')
    ->prefix('v1/')
    ->group(callback: function() {
        Route::post('register', [RegisterController::class, 'register'])
            ->name('register');
        Route::post('users/activate', [RegisterController::class, 'verify'])
            ->name('users.activate');
        Route::post('login', [LoginController::class, 'login'])
            ->name('login');
        Route::post('passwords/recover', [
            PasswordRecoveryController::class,
            'sendRecoveryCode'
        ])->name('passwords.recover');
        Route::post('passwords/reset', [
            PasswordRecoveryController::class,
            'resetPassword'
        ])->name('passwords.reset');

        Route::name('articles.')->group(
            base_path('routes/api/v1/articles.api.php')
        );
    });
