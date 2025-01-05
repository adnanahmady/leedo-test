<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;

class IncorrectRecoveryCodeException extends ValidationException
{
    public static function throwIf(mixed $condition, string $message): void
    {
        if (!$condition) {
            return;
        }
        throw ValidationException::withMessages(['code' => [$message]]);
    }
}
