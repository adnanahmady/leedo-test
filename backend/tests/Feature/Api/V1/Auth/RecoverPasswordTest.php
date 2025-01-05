<?php

namespace Tests\Feature\Api\V1\Auth;

use App\CacheManagers\Auth\PasswordRecoveryCodeManager;
use App\CacheManagers\Auth\RegisterCodeManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecoverPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_wrong_code_should_throw_validation_exception(): void
    {
        $email = 'dummy@dummy.com';
        User::firstOrCreate(['email' => $email], []);
        $data = [
            'email' => $email,
            'code' => '033422',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(route('api.v1.passwords.reset'), $data);

        $response->assertUnprocessable();
        $response->assertJsonPath(
            'errors.code.0',
            'Recovery code is incorrect.'
        );
    }

    public function test_it_should_verify_the_code(): void
    {
        $this->withoutExceptionHandling();
        $email = 'dummy@dummy.com';
        User::firstOrCreate(['email' => $email], []);
        $code = (new PasswordRecoveryCodeManager())->get($email);
        $data = [
            'email' => $email,
            'code' => $code,
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(route('api.v1.passwords.reset'), $data);

        $response->assertStatus(200);
        $response->assertJsonPath(
            'data.message',
            'Password did reset successfully.'
        );
    }
}
