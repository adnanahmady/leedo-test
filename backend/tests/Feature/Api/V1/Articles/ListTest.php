<?php

namespace Tests\Feature\Api\V1\Articles;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class ListTest extends TestCase
{
    use RefreshDatabase;

    #[TestWith(['filters' => ['content' => 'comic'], 'expectedCount' => 1])]
    #[TestWith(['filters' => ['content' => 'romance'], 'expectedCount' => 1])]
    #[TestWith(['filters' => ['content' => 'drama'], 'expectedCount' => 2])]
    public function test_user_should_be_able_to_filter_by_content(
        array $filters,
        int $expectedCount,
    ): void {
        $this->withoutExceptionHandling();
        Article::factory()->create(['content' => 'some content for comic']);
        Article::factory()->create(['content' => 'some content for romance and drama']);
        Article::factory()->create(['content' => 'some content for drama']);

        $response = $this->getJson(route('api.v1.articles.list', $filters));

        $response->assertJsonCount($expectedCount, 'data');
    }

    #[TestWith(['filters' => ['category' => 'comic'], 'expectedCount' => 1])]
    #[TestWith(['filters' => ['category' => 'romance'], 'expectedCount' => 1])]
    #[TestWith(['filters' => ['category' => 'drama'], 'expectedCount' => 2])]
    public function test_user_should_be_able_to_filter_by_category_name(
        array $filters,
        int $expectedCount,
    ): void {
        $this->withoutExceptionHandling();
        $c1 = Category::factory()->create(['name' => 'comic']);
        Article::factory()->create(['category_id' => $c1->getKey()]);
        $c2 = Category::factory()->create(['name' => 'drama and romance']);
        Article::factory()->create(['category_id' => $c2->getKey()]);
        $c3 = Category::factory()->create(['name' => 'drama']);
        Article::factory()->create(['category_id' => $c3->getKey()]);

        $response = $this->getJson(route('api.v1.articles.list', $filters));

        $response->assertJsonCount($expectedCount, 'data');
    }

    #[TestWith(['filters' => ['writer' => 'joh'], 'expectedCount' => 1])]
    #[TestWith(['filters' => ['writer' => 'reza'], 'expectedCount' => 1])]
    #[TestWith(['filters' => ['writer' => 'mohammad'], 'expectedCount' => 2])]
    public function test_user_should_be_able_to_filter_by_writer_name(
        array $filters,
        int $expectedCount,
    ): void {
        $this->withoutExceptionHandling();
        $u1 = User::factory()->create(['name' => 'john']);
        Article::factory()->create(['writer_id' => $u1->getKey()]);
        $u2 = User::factory()->create(['name' => 'mohammad']);
        Article::factory()->create(['writer_id' => $u2->getKey()]);
        $u3 = User::factory()->create(['name' => 'mohammad reza']);
        Article::factory()->create(['writer_id' => $u3->getKey()]);

        $response = $this->getJson(route('api.v1.articles.list', $filters));

        $response->assertJsonCount($expectedCount, 'data');
    }

    public function test_articles_items_should_be_in_expected_form(): void
    {
        $this->withoutExceptionHandling();
        $article = Article::factory()->create();

        $response = $this->getJson('/api/v1/articles');

        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('data.0.title', $article->title)
            ->where('data.0.slug', $article->slug)
            ->where('data.0.content', $article->content)
            ->where('data.0.writer.name', $article->writer->name)
            ->where('data.0.writer.email', $article->writer->email)
            ->where('data.0.category.name', $article->category->name)
        );
    }

    public function test_the_user_should_be_able_to_get_the_list_of_articles(): void
    {
        Article::factory()->count($count = 3)->create();

        $response = $this->getJson('/api/v1/articles');

        $response->assertStatus(200);
        $response->assertJsonCount($count, 'data');
    }
}
