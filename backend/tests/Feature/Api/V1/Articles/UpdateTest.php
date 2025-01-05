<?php

namespace Tests\Feature\Api\V1\Articles;

use App\Models\Article;
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
                    'title' => 'article title',
                    'slug' => 'duplicate article slug',
                    'content' => 'article content',
                    'category_id' => fn(Category $c) => $c->getKey(),
                ],
                'expectedErrorField' => 'slug',
                'expectedErrorCount' => 1,
            ],

            'category_id should exist in the database' => [
                'data' => [
                    'title' => 'article title',
                    'slug' => 'article slug',
                    'content' => 'article content',
                    'category_id' => fn(Category $c) => 90334933,
                ],
                'expectedErrorField' => 'category_id',
                'expectedErrorCount' => 1,
            ],

            'category_id should be required' => [
                'data' => [
                    'title' => 'article title',
                    'slug' => 'article slug',
                    'content' => 'article content',
                ],
                'expectedErrorField' => 'category_id',
                'expectedErrorCount' => 1,
            ],

            'content should be required' => [
                'data' => [
                    'title' => 'article title',
                    'slug' => 'article slug',
                    'category_id' => fn(Category $c) => $c->getKey(),
                ],
                'expectedErrorField' => 'content',
                'expectedErrorCount' => 1,
            ],

            'slug should be required' => [
                'data' => [
                    'title' => 'article title',
                    'content' => 'article content',
                    'category_id' => fn(Category $c) => $c->getKey(),
                ],
                'expectedErrorField' => 'slug',
                'expectedErrorCount' => 1,
            ],

            'title should be required' => [
                'data' => [
                    'slug' => 'article slug',
                    'content' => 'article content',
                    'category_id' => fn(Category $c) => $c->getKey(),
                ],
                'expectedErrorField' => 'title',
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
        $category = Category::factory()->create();
        $article = Article::factory()->create(['writer_id' => $user->getKey()]);
        Article::factory()->create(['slug' => 'duplicate article slug']);
        $data['article'] = $article;

        if (key_exists('category_id', $data)) {
            $data['category_id'] = $data['category_id']($category);
        }

        // Act
        $response = $this->putJson(route('api.v1.articles.update', $data));

        // Assert
        $response->assertUnprocessable();
        $response->assertJsonValidationErrorFor($expectedErrorField);
        $response->assertJsonCount(
            $expectedErrorCount,
            "errors.$expectedErrorField"
        );
    }

    public function test_only_the_writer_should_be_able_to_edit_its_article(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $category = Category::factory()->create();
        $article = Article::factory()->create();
        $data = [
            'article' => $article,
            'title' => 'article title',
            'slug' => 'article slug',
            'content' => 'article content',
            'category_id' => $category->getKey(),
        ];

        $response = $this->putJson(route('api.v1.articles.update', $data));

        $response->assertForbidden();
    }

    public function test_only_authenticated_users_should_be_able_to_create_article(): void
    {
        $category = Category::factory()->create();
        $article = Article::factory()->create();
        $data = [
            'article' => $article,
            'title' => 'article title',
            'slug' => 'article slug',
            'content' => 'article content',
            'category_id' => $category->getKey(),
        ];

        $response = $this->putJson(route('api.v1.articles.update', $data));

        $response->assertUnauthorized();
    }

    public function test_articles_items_should_be_in_expected_form(): void
    {
        $this->withoutExceptionHandling();
        Sanctum::actingAs($user = User::factory()->create());
        $category = Category::factory()->create();
        $article = Article::factory()->create(['writer_id' => $user->getKey()]);
        $data = [
            'article' => $article,
            'title' => 'article title',
            'slug' => 'article slug',
            'content' => 'article content',
            'category_id' => $category->getKey(),
        ];

        $response = $this->putJson(route('api.v1.articles.update', $data));

        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('data.title', $data['title'])
            ->where('data.slug', $data['slug'])
            ->where('data.content', $data['content'])
            ->where('data.writer.name', $article->writer->name)
            ->where('data.writer.email', $article->writer->email)
            ->where('data.category.name', $category->name)
        );
    }

    public function test_the_user_should_be_able_to_get_the_list_of_articles(): void
    {
        Sanctum::actingAs($user = User::factory()->create());
        $category = Category::factory()->create();
        $article = Article::factory()->create(['writer_id' => $user->getKey()]);
        $data = [
            'article' => $article,
            'title' => 'article title',
            'slug' => 'article slug',
            'content' => 'article content',
            'category_id' => $category->getKey(),
        ];

        $response = $this->putJson(route('api.v1.articles.update', $data));

        $response->assertOk();
        $this->assertDatabaseHas(Article::class, [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'category_id' => $data['category_id'],
        ]);
    }
}
