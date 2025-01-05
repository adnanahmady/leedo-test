<?php

namespace App\Http\Services\Api\V1\Auth;

use App\CacheManagers\Auth\PasswordRecoveryCodeManager;
use App\Exceptions\IncorrectRecoveryCodeException;
use App\Http\Requests\Api\V1\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\V1\Auth\SendRecoverCodeRequest;
use App\Mail\RecoverPasswordEmail;
use App\Repositories\Auth\PasswordRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class ResetPasswordService
{
    public function __construct(
        private ResetPasswordRequest $request,
        private PasswordRecoveryCodeManager $codeManager,
        private PasswordRepositoryInterface $repository,
    ) {}

    public function reset(): void
    {
        $code = $this->codeManager->consume($this->request->email);

        IncorrectRecoveryCodeException::throwIf(
            $code === null,
            __('Recovery code is incorrect.')
        );

        $this->repository->reset(
            email: $this->request->email,
            password: $this->request->password,
        );
    }
}
