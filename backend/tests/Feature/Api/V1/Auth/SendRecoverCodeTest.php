<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Http\Services\Api\V1\Auth\SendRecoveryCodeService;
use App\Mail\RecoverPasswordEmail;
use App\Mail\UserRegisteredEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SendRecoverCodeTest extends TestCase
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
            'email' => 'dummy@dummy.com',
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
        User::factory()->create($data);

        $this->postJson(route('api.v1.passwords.recover'), $data);

        $this->assertNotNull(
            cache('recovery:password:' . $data['email'])
        );
    }

    public function test_with_wrong_email_a_code_should_not_get_created()
    {
        $this->withoutExceptionHandling();
        $data = ['email' => 'dummy@dummy.com'];
        Mail::fake();

        $response = $this->postJson(route('api.v1.passwords.recover'), $data);

        $response->assertOk();
        Mail::assertNotQueued(RecoverPasswordEmail::class);
        $this->assertNull(
            cache('recovery:password:' . $data['email'])
        );
    }

    public function test_a_code_should_get_send_to_users_email()
    {
        $this->withoutExceptionHandling();
        $data = ['email' => 'dummy@dummy.com'];
        User::factory()->create($data);
        Mail::fake();

        $this->postJson(route('api.v1.passwords.recover'), $data);

        Mail::assertQueuedCount(1);
        Mail::assertQueued(
            RecoverPasswordEmail::class,
            fn($mail) => $mail->to($data['email'])
        );
    }

    public function test_user_should_be_able_to_request_recovery_code(): void
    {
        $this->withoutExceptionHandling();
        $data = ['email' => 'dummy@dummy.com'];
        User::factory()->create($data);

        $response = $this->postJson(route('api.v1.passwords.recover'), $data);

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) => $json
            ->where('data.message', 'Recovery code sent successfully.')
        );
    }
}
