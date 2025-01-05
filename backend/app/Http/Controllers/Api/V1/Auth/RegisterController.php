<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\Api\V1\Auth\UserActivationService;
use App\Http\Services\Api\V1\Auth\RegisterService;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function register(RegisterService $service): JsonResponse
    {
        $service->register();

        return new JsonResponse(['data' => [
            'message' => __('Registered successfully.')
        ]]);
    }

    public function verify(UserActivationService $service): JsonResponse
    {
        $service->verify();

        return new JsonResponse(['data' => [
            'message' => __('Registration completed successfully.')
        ]]);
    }
}
