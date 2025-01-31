<?php

namespace App\CacheManagers\Auth;

class RegisterCodeManager extends BaseCodeManager
{
    protected function key(string $email): string
    {
        return 'register:email:' . $email;
    }
}
