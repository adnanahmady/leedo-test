<?php

namespace App\CacheManagers\Auth;

class PasswordRecoveryCodeManager extends BaseCodeManager
{
    protected function key(string $email): string
    {
        return 'recovery:password:' . $email;
    }
}
