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

        $response = $this->getJson(route('api.v1.articles.show', $article->getRouteKey()));

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->id,
                'attributes' => [
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'content' => $article->content,
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article->getRouteKey())
                ]
            ]
        ]);
    }

    /** @test */
    public function can_fetch_all_articles()
    {
        $this->withoutExceptionHandling();

        $articles = factory(Article::class)->times(3)->create();

        $response = $this->getJson(route('api.v1.articles.index'));

        $response->assertExactJson([
            'data' => [
                [
                    'type' => 'articles',
                    'id' => (string) $articles[0]->id,
                    'attributes' => [
                        'title' => $articles[0]->title,
                        'slug' => $articles[0]->slug,
                        'content' => $articles[0]->content,
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.show', $articles[0]->getRouteKey())
                    ]
                ],
                [
                    'type' => 'articles',
                    'id' => (string) $articles[1]->id,
                    'attributes' => [
                        'title' => $articles[1]->title,
                        'slug' => $articles[1]->slug,
                        'content' => $articles[1]->content,
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.show', $articles[1]->getRouteKey())
                    ]
                ],
                [
                    'type' => 'articles',
                    'id' => (string) $articles[2]->id,
                    'attributes' => [
                        'title' => $articles[2]->title,
                        'slug' => $articles[2]->slug,
                        'content' => $articles[2]->content,
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.show', $articles[2]->getRouteKey())
                    ]
                ]
            ],
            'links' => [
                'self' => route('api.v1.articles.index')
            ],
            'meta' => [
                'articles_count' => 3
            ]
        ]);
    }
}
