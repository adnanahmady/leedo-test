<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Mail\UserRegisteredEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public static function dataProviderForValidationCode(): array
    {
        return [
            'password be at least 8 characters' => [
                'data' => [
                    'email' => 'dummy@dummycom',
                    'password' => 'secret1',
                ],
                'expectedErrorField' => 'password',
                'expectedErrorCount' => 1,
            ],

            'email should have correct format' => [
                'data' => [
                    'email' => 'dummy@dummycom',
                    'password' => 'password',
                ],
                'expectedErrorField' => 'email',
                'expectedErrorCount' => 1,
            ],

            'password should be required' => [
                'data' => [
                    'email' => 'dummy@dummy.com',
                ],
                'expectedErrorField' => 'password',
                'expectedErrorCount' => 1,
            ],

            'email should be required' => [
                'data' => [
                    'password' => 'password',
                ],
                'expectedErrorField' => 'email',
                'expectedErrorCount' => 1,
            ],

        ];
    }

    #[DataProvider('dataProviderForValidationCode')]
    public function testDataValidation(
        array $data,
        string $expectedErrorField,
        int $expectedErrorCount
    ): void {
        User::factory()->create([
            'email' => 'dummy@dummy.com',
            'password' => 'password'
        ]);

        $response = $this->postJson(route('api.v1.login'), $data);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrorFor($expectedErrorField);
        $response->assertJsonCount(
            $expectedErrorCount,
            "errors.$expectedErrorField"
        );
    }

    public function test_user_should_be_able_to_login(): void
    {
        $data = ['email' => 'dummy@dummy.com', 'password' => 'password'];
        $user = User::factory()->create($data);

        $response = $this->postJson(route('api.v1.login'), $data);

        $response->assertStatus(200);
        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->has('data.access_token')
                ->where('data.token_type', 'Bearer')
                ->where('meta.user.name', $user->name)
                ->where('meta.user.last_name', $user->last_name)
                ->where('meta.user.email', $user->email)
        );
    }
}
