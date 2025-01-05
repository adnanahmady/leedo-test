<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Auth\UserLoggedInResource;
use App\Http\Services\Api\V1\Auth\LoginService;
use App\Http\Services\Api\V1\Auth\UserActivationService;
use App\Http\Services\Api\V1\Auth\RegisterService;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function login(
        LoginService $service,
    ): UserLoggedInResource {
        $dto = $service->login();

        return new UserLoggedInResource($dto);
    }
}
