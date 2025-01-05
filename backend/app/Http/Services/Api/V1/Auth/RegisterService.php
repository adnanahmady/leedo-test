<?php

namespace App\Http\Services\Api\V1\Auth;

use App\CacheManagers\Auth\RegisterCodeManager;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Mail\UserRegisteredEmail;
use App\Models\User;
use App\Repositories\Auth\RegisterRepositoryInterface;
use Illuminate\Support\Facades\Mail;

class RegisterService
{
    public function __construct(
        private RegisterRequest $request,
        private RegisterCodeManager $codeManager,
        private RegisterRepositoryInterface $repository,
    ) {}

    public function register(): void
    {
        $this->repository->register($this->request->email);

        $code = $this->codeManager->get($this->request->email);

        Mail::to($this->request->email)->queue(
            new UserRegisteredEmail($code)
        );
    }
}
