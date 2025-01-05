<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\Api\BaseFormRequest;

class ResetPasswordRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|min:6|max:6',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ];
    }
}
