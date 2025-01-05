<?php

namespace App\Http\Services\Api\V1\Auth;

use App\CacheManagers\Auth\PasswordRecoveryCodeManager;
use App\Http\Requests\Api\V1\Auth\SendRecoverCodeRequest;
use App\Mail\RecoverPasswordEmail;
use App\Repositories\Auth\PasswordRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class SendRecoveryCodeService
{
    public function __construct(
        private SendRecoverCodeRequest $request,
        private PasswordRecoveryCodeManager $codeManager,
        private PasswordRepositoryInterface $repository,
    ) {}

    public function sendCode(): void
    {
        $user = $this->repository->find($this->request->email);

        if ($user === null) {
            return;
        }

        $code = $this->codeManager->get($this->request->email);

        Mail::to($this->request->email)->queue(
            new RecoverPasswordEmail($code)
        );
    }
}
