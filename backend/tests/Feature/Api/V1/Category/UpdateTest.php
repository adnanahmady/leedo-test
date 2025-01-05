<?php

namespace Tests\Feature\Api\V1\Category;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class UpdateTest extends TestCase
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
                ],
                'expectedErrorField' => 'slug',
                'expectedErrorCount' => 1,
            ],

            'name should be required' => [
                'data' => [
                    'slug' => 'category slug',
                ],
                'expectedErrorField' => 'name',
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
        // Arrange
        Sanctum::actingAs($user = User::factory()->create());
        $category = Category::factory()->create(['owner_id' => $user->getKey()]);
        Category::factory()->create(['slug' => 'duplicate category slug']);
        $data['category'] = $category;

        if (key_exists('category_id', $data)) {
            $data['category_id'] = $data['category_id']($category);
        }

        // Act
        $response = $this->putJson(route('api.v1.categories.update', $data));

        // Assert
        $response->assertUnprocessable();
        $response->assertJsonValidationErrorFor($expectedErrorField);
        $response->assertJsonCount(
            $expectedErrorCount,
            "errors.$expectedErrorField"
        );
    }

    public function test_only_the_writer_should_be_able_to_edit_its_category(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $category = Category::factory()->create();
        $data = [
            'category' => $category,
            'name' => 'category name',
            'slug' => 'category slug',
        ];

        $response = $this->putJson(route('api.v1.categories.update', $data));

        $response->assertForbidden();
    }

    public function test_only_authenticated_users_should_be_able_to_create_category(): void
    {
        $category = Category::factory()->create();
        $data = [
            'category' => $category,
            'name' => 'category name',
            'slug' => 'category slug',
        ];

        $response = $this->putJson(route('api.v1.categories.update', $data));

        $response->assertUnauthorized();
    }

    public function test_categories_items_should_be_in_expected_form(): void
    {
        $this->withoutExceptionHandling();
        Sanctum::actingAs($user = User::factory()->create());
        $category = Category::factory()->create(['owner_id' => $user->getKey()]);
        $data = [
            'category' => $category,
            'name' => 'category name',
            'slug' => 'category slug',
        ];

        $response = $this->putJson(route('api.v1.categories.update', $data));

        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('data.name', $data['name'])
            ->where('data.slug', $data['slug'])
            ->where('data.owner.name', $category->owner->name)
            ->where('data.owner.email', $category->owner->email)
        );
    }

    public function test_the_user_should_be_able_to_get_the_list_of_categories(): void
    {
        Sanctum::actingAs($user = User::factory()->create());
        $category = Category::factory()->create(['owner_id' => $user->getKey()]);
        $data = [
            'category' => $category,
            'name' => 'category name',
            'slug' => 'category slug',
        ];

        $response = $this->putJson(route('api.v1.categories.update', $data));

        $response->assertOk();
        $this->assertDatabaseHas(Category::class, [
            'name' => $data['name'],
            'slug' => $data['slug'],
        ]);
    }
}
