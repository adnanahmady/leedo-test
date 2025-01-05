<?php

namespace App\Http\Services\Api\V1\Auth;

use App\CacheManagers\Auth\RegisterCodeManager;
use App\Exceptions\IncorrectActivationCodeException;
use App\Http\Requests\Api\V1\Auth\ActivateUserRequest;
use App\Repositories\Auth\RegisterRepositoryInterface;

class UserActivationService
{
    public function __construct(
        private ActivateUserRequest $request,
        private RegisterCodeManager $cacheManager,
        private RegisterRepositoryInterface $repository,
    ) {}

    public function verify(): void
    {
        $code = $this->cacheManager->consume($this->request->email);

        IncorrectActivationCodeException::throwIf(
            $code === null,
            __('Activation code is incorrect.')
        );

        $this->repository->activate(
            email: $this->request->email,
            name: $this->request->name,
            lastName: $this->request->last_name,
            password: $this->request->password,
        );
    }
}
