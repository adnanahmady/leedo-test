<?php

namespace Tests\Feature\Api\V1\Auth;

use App\CacheManagers\Auth\RegisterCodeManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_wrong_code_should_throw_validation_exception(): void
    {
        $email = 'dummy@dummy.com';
        User::firstOrCreate(['email' => $email], []);
        $data = [
            'email' => $email,
            'code' => '033422',
            'name' => 'robert',
            'last_name' => 'due',
            'password' => 'password'
        ];

        $response = $this->postJson(route('api.v1.users.activate'), $data);

        $response->assertUnprocessable();
        $response->assertJsonPath(
            'errors.code.0',
            'Activation code is incorrect.'
        );
    }

    public function test_it_should_verify_the_code(): void
    {
        $this->withoutExceptionHandling();
        $email = 'dummy@dummy.com';
        User::firstOrCreate(['email' => $email], []);
        $code = (new RegisterCodeManager())->get($email);
        $data = [
            'email' => $email,
            'code' => $code,
            'name' => 'robert',
            'last_name' => 'due',
            'password' => 'password'
        ];

        $response = $this->postJson(route('api.v1.users.activate'), $data);

        $response->assertStatus(200);
        $response->assertJsonPath(
            'data.message',
            'Registration completed successfully.'
        );
        $this->assertDatabaseHas(User::class, [
            'email' => $data['email'],
            'name' => $data['name'],
            'last_name' => $data['last_name'],
        ]);
    }
}
