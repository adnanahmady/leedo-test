<?php

namespace App\CacheManagers\Auth;

class RegisterCodeManager
{
    public function consume(string $email): ?string
    {
        return cache($this->key($email));
    }

    public function get(string $email): string
    {
        return cache()->remember(
            $this->key($email),
            now()->addMinutes(2)->timestamp,
            fn() => str_pad(
                string: random_int(0, 999999),
                length: 6,
                pad_string: '0',
                pad_type: STR_PAD_LEFT
            )
        );
    }

    private function key(string $email): string
    {
        return 'register:email:' . $email;
    }
}
