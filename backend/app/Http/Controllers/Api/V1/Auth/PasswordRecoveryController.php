<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Auth\UserLoggedInResource;
use App\Http\Services\Api\V1\Auth\LoginService;
use App\Http\Services\Api\V1\Auth\ResetPasswordService;
use App\Http\Services\Api\V1\Auth\SendRecoveryCodeService;
use App\Http\Services\Api\V1\Auth\UserActivationService;
use App\Http\Services\Api\V1\Auth\RegisterService;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class PasswordRecoveryController extends Controller
{
    public function sendRecoveryCode(
        SendRecoveryCodeService $service,
    ): JsonResponse {
        $service->sendCode();

        return new JsonResponse(['data' => [
            'message' => __('Recovery code sent successfully.')
        ]]);
    }

    public function resetPassword(
        ResetPasswordService $service,
    ): JsonResponse {
        $service->reset();

        return new JsonResponse(['data' => [
            'message' => __('Password did reset successfully.')
        ]]);
    }
}
