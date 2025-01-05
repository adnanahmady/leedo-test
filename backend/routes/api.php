<?php

use App\Http\Controllers\Api\V1\Auth\CodeVerificationController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api.v1.')
    ->prefix('v1/')
    ->group(function() {
        Route::post('register', [
            RegisterController::class,
            'register'
        ])->name('register');
        Route::post('users/activate', [
            RegisterController::class,
            'verify'
        ])->name('users.activate');
    });
