<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guess_users_can_create_article()
    {
        $article = factory(Article::class)->raw(['user_id' => null]);

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'attributes' => $article
            ]
        ])->post(route('api.v1.articles.create'))->assertStatus(401);

        $this->assertDatabaseMissing('articles', $article);
    }

    /** @test */
    public function authenticated_users_can_create_article()
    {
        $article = factory(Article::class)->raw(['user_id' => null]);
        $user = factory(User::class)->create();

        Sanctum::actingAs($user);

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'attributes' => $article
            ]
        ])->post(route('api.v1.articles.create'))->assertCreated();

        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => $article['title'],
            'slug' => $article['slug'],
            'content' => $article['content']
        ]);
    }

    /** @test */
    public function title_article_is_required()
    {
        $article = factory(Article::class)->raw(['title' => '']);

        Sanctum::actingAs(factory(User::class)->create());

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'attributes' => $article
            ]
        ])->post(route('api.v1.articles.create'))->assertStatus(422);

        $this->assertDatabaseMissing('articles', $article);
    }

    /** @test */
    public function content_article_is_required()
    {
        $article = factory(Article::class)->raw(['content' => '']);

        Sanctum::actingAs(factory(User::class)->create());

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'attributes' => $article
            ]
        ])->post(route('api.v1.articles.create'))->assertStatus(422);

        $this->assertDatabaseMissing('articles', $article);
    }

    /** @test */
    public function slug_article_is_required()
    {
        $article = factory(Article::class)->raw(['slug' => '']);
        Sanctum::actingAs(factory(User::class)->create());

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'attributes' => $article
            ]
        ])->post(route('api.v1.articles.create'))->assertStatus(422);

        $this->assertDatabaseMissing('articles', $article);
    }

    /** @test */
    public function slug_article_must_be_unique()
    {
        factory(Article::class)->create(['slug' => 'slug-test']);
        $article = factory(Article::class)->raw(['slug' => 'slug-test']);

        Sanctum::actingAs(factory(User::class)->create());

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'attributes' => $article
            ]
        ])->post(route('api.v1.articles.create'))->assertStatus(422);

        $this->assertDatabaseMissing('articles', $article);
    }

    /** @test */
    public function slug_article_must_only_contain_letters_numbers_and_dashes()
    {
        $article = factory(Article::class)->raw(['slug' => '#$^^%$']);

        Sanctum::actingAs(factory(User::class)->create());

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'attributes' => $article
            ]
        ])->post(route('api.v1.articles.create'))->assertStatus(422);

        $this->assertDatabaseMissing('articles', $article);
    }

    /** @test */
    public function slug_article_must_not_contain_underscores()
    {
        $article = factory(Article::class)->raw(['slug' => 'with_underscore']);

        Sanctum::actingAs(factory(User::class)->create());

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'attributes' => $article
            ]
        ])->post(route('api.v1.articles.create'))->assertStatus(422);

        $this->assertDatabaseMissing('articles', $article);
    }

    /** @test */
    public function slug_article_must_not_start_with_dashes()
    {
        $article = factory(Article::class)->raw(['slug' => '-start-with-dashes']);

        Sanctum::actingAs(factory(User::class)->create());

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'attributes' => $article
            ]
        ])->post(route('api.v1.articles.create'))->assertStatus(422);

        $this->assertDatabaseMissing('articles', $article);
    }

    /** @test */
    public function slug_article_must_not_finish_with_dashes()
    {
        $article = factory(Article::class)->raw(['slug' => 'finish-with-dashes-']);

        Sanctum::actingAs(factory(User::class)->create());

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'attributes' => $article
            ]
        ])->post(route('api.v1.articles.create'))->assertStatus(422);

        $this->assertDatabaseMissing('articles', $article);
    }
}
