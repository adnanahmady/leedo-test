<?php

namespace Tests\Feature\Api\V1\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public static function dataProviderForValidationCode(): array
    {
        return [

            'slug should be unique' => [
                'data' => [
                    'name' => 'category name',
                    'slug' => 'duplicate category slug',
                ],
                'expectedErrorField' => 'slug',
                'expectedErrorCount' => 1,
            ],

            'slug should be required' => [
                'data' => [
                    'name' => 'category name',
                    'content' => 'category content',
                    'category_id' => fn(Category $c) => $c->getKey(),
                ],
                'expectedErrorField' => 'slug',
                'expectedErrorCount' => 1,
            ],

            'name should be required' => [
                'data' => [
                    'slug' => 'category slug',
                    'content' => 'category content',
                ],
                'expectedErrorField' => 'name',
                'expectedErrorCount' => 1,
            ],

        ];
    }

    #[DataProvider('dataProviderForValidationCode')]
    public function test_data_validation(
        array $data,
        string $expectedErrorField,
        int $expectedErrorCount
    ): void {
        // Arrange
        Sanctum::actingAs(User::factory()->create());
        Category::factory()->create(['slug' => 'duplicate category slug']);

        // Act
        $response = $this->postJson(route('api.v1.categories.store', $data));

        // Assert
        $response->assertUnprocessable();
        $response->assertJsonValidationErrorFor($expectedErrorField);
        $response->assertJsonCount(
            $expectedErrorCount,
            "errors.$expectedErrorField"
        );
    }

    public function test_only_authenticated_users_should_be_able_to_create_category(): void
    {
        $data = [
            'name' => 'category name',
            'slug' => 'category slug',
        ];

        $response = $this->postJson(route('api.v1.categories.store', $data));

        $response->assertUnauthorized();
    }

    public function test_category_items_should_be_in_expected_form(): void
    {
        $this->withoutExceptionHandling();
        Sanctum::actingAs($user = User::factory()->create());
        $data = [
            'name' => 'category name',
            'slug' => 'category slug',
        ];

        $response = $this->postJson(route('api.v1.categories.store', $data));

        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('data.name', $data['name'])
            ->where('data.slug', $data['slug'])
            ->where('data.owner.name', $user->name)
            ->where('data.owner.email', $user->email)
        );
    }

    public function test_the_user_should_be_able_to_get_the_list_of_categories(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $data = [
            'name' => 'category name',
            'slug' => 'category slug',
        ];

        $response = $this->postJson(route('api.v1.categories.store', $data));

        $response->assertCreated();
        $this->assertDatabaseHas(Category::class, [
            'name' => $data['name'],
            'slug' => $data['slug'],
        ]);
    }
}
