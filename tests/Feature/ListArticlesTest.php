<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Article;
use Tests\TestCase;

class ListArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_a_specific_article()
    {
        $this->withoutExceptionHandling();

        $article = factory(Article::class)->create();

        $response = $this->jsonApi()->get(route('api.v1.articles.read', $article->getRouteKey()));

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->id,
                'attributes' => [
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'content' => $article->content,
                    'created-at' => $article->created_at->toAtomString(),
                    'updated-at' => $article->updated_at->toAtomString(),
                ],
                'links' => [
                    'self' => route('api.v1.articles.read', $article->getRouteKey())
                ]
            ]
        ]);
    }

    /** @test */
    public function can_fetch_all_articles()
    {
        $this->withoutExceptionHandling();

        $articles = factory(Article::class)->times(3)->create();

        $response = $this->jsonApi()->get(route('api.v1.articles.index'));

        $response->assertJsonFragment([
            'data' => [
                [
                    'type' => 'articles',
                    'id' => (string) $articles[0]->id,
                    'attributes' => [
                        'title' => $articles[0]->title,
                        'slug' => $articles[0]->slug,
                        'content' => $articles[0]->content,
                        'created-at' => $articles[0]->created_at->toAtomString(),
                        'updated-at' => $articles[0]->updated_at->toAtomString(),
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.read', $articles[0]->getRouteKey())
                    ]
                ],
                [
                    'type' => 'articles',
                    'id' => (string) $articles[1]->id,
                    'attributes' => [
                        'title' => $articles[1]->title,
                        'slug' => $articles[1]->slug,
                        'content' => $articles[1]->content,
                        'created-at' => $articles[1]->created_at->toAtomString(),
                        'updated-at' => $articles[1]->updated_at->toAtomString(),
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.read', $articles[1]->getRouteKey())
                    ]
                ],
                [
                    'type' => 'articles',
                    'id' => (string) $articles[2]->id,
                    'attributes' => [
                        'title' => $articles[2]->title,
                        'slug' => $articles[2]->slug,
                        'content' => $articles[2]->content,
                        'created-at' => $articles[2]->created_at->toAtomString(),
                        'updated-at' => $articles[2]->updated_at->toAtomString(),
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.read', $articles[2]->getRouteKey())
                    ]
                ]
            ]
        ]);
    }
}
