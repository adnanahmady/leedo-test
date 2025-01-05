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

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public static function dataProviderForValidationCode(): array
    {
        return [

            'email should have correct format' => [
                'data' => [
                    'email' => 'incorrect-email',
                ],
                'expectedErrorField' => 'email',
                'expectedErrorCount' => 1,
            ],

            'email should not exist in system' => [
                'data' => [
                    'email' => 'dummy@dummy2.com',
                ],
                'expectedErrorField' => 'email',
                'expectedErrorCount' => 1,
            ],

            'email should be required' => [
                'data' => [],
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
            'email' => 'dummy@dummy2.com',
        ]);

        $response = $this->postJson(route('api.v1.register'), $data);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrorFor($expectedErrorField);
        $response->assertJsonCount(
            $expectedErrorCount,
            "errors.$expectedErrorField"
        );
    }

    public function test_the_code_should_get_stored_to_later_user()
    {
        $this->withoutExceptionHandling();
        $data = ['email' => 'dummy@dummy.com'];

        $this->postJson(route('api.v1.register'), $data);

        $this->assertNotNull(
            cache('register:email:' . $data['email'])
        );
    }

    public function test_a_code_should_get_send_to_users_email()
    {
        $this->withoutExceptionHandling();
        $data = ['email' => 'dummy@dummy.com'];
        Mail::fake();

        $this->postJson(route('api.v1.register'), $data);

        Mail::assertQueuedCount(1);
        Mail::assertQueued(
            UserRegisteredEmail::class,
            fn($mail) => $mail->to($data['email'])
        );
    }

    public function test_user_should_be_able_to_register(): void
    {
        $this->withoutExceptionHandling();
        $data = ['email' => 'dummy@dummy.com'];

        $response = $this->postJson(route('api.v1.register'), $data);

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json
            ->where('data.message', 'Registered successfully.')
        );
        $this->assertDatabaseHas(User::class, [
            'email' => $data['email'],
        ]);
    }
}
