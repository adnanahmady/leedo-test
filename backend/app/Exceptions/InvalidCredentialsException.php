<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;

class InvalidCredentialsException extends ValidationException
{
    public static function throwIf(mixed $condition, array $message): void
    {
        if (!$condition) {
            return;
        }
        throw ValidationException::withMessages($message);
    }
}
