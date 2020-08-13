<?php

namespace Tests\Feature\Articles;

use Tests\TestCase;
use App\Models\Article;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guess_users_cannon_update_articles()
    {
        $article = factory(Article::class)->create();

        $this->jsonApi()->patch(route('api.v1.articles.update', $article))
            ->assertStatus(401);
    }

    /** @test */
    public function authenticated_users_can_update_their_articles()
    {
        $article = factory(Article::class)->create(['title' => 'Article 1']);

        $this->assertEquals('Article 1', $article->title);

        Sanctum::actingAs($article->user);

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'id' => $article->getRouteKey(),
                'attributes' => [
                    'title' => 'Article 2',
                    'slug' => 'slug-2',
                    'content' => 'content 2'
                ]
            ]
        ])->patch(route('api.v1.articles.update', $article))->assertStatus(200);

        $this->assertDatabaseHas('articles', [
            'title' => 'Article 2',
            'slug' => 'slug-2',
            'content' => 'content 2'
        ]);
    }

    /** @test */
    public function authenticated_users_cannon_update_others_articles()
    {
        $article = factory(Article::class)->create(['title' => 'Article 1']);

        Sanctum::actingAs(factory(User::class)->create());

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'id' => $article->getRouteKey(),
                'attributes' => [
                    'title' => 'Article 2',
                    'slug' => 'slug-2',
                    'content' => 'content 2'
                ]
            ]
        ])->patch(route('api.v1.articles.update', $article))->assertStatus(403);

        $this->assertDatabaseMissing('articles', [
            'title' => 'Article 2',
            'slug' => 'slug-2',
            'content' => 'content 2'
        ]);
    }

    /** @test */
    public function authenticated_users_can_update_title_field_of_articles()
    {
        $article = factory(Article::class)->create(['title' => 'Article 1']);

        $this->assertEquals('Article 1', $article->title);

        Sanctum::actingAs($article->user);

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'id' => $article->getRouteKey(),
                'attributes' => [
                    'title' => 'Article 2',
                ]
            ]
        ])->patch(route('api.v1.articles.update', $article))->assertStatus(200);

        $this->assertDatabaseHas('articles', [
            'title' => 'Article 2',
        ]);
    }
}
