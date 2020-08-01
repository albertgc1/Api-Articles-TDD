<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_article()
    {
        $article = factory(Article::class)->raw();

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'attributes' => $article
            ]
        ])->post(route('api.v1.articles.create'))->assertCreated();

        $this->assertDatabaseHas('articles', $article);
    }

    /** @test */
    public function title_article_is_required()
    {
        $article = factory(Article::class)->raw(['title' => '']);

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

        $this->jsonApi()->content([
            'data' => [
                'type' => 'articles',
                'attributes' => $article
            ]
        ])->post(route('api.v1.articles.create'))->assertStatus(422);

        $this->assertDatabaseMissing('articles', $article);
    }
}
