<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaginateArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_paginated_articles()
    {
        $articles = factory(Article::class)->times(10)->create();

        $url = route('api.v1.articles.index', ['page[size]' => 2, 'page[number]' => 3]);

        $response = $this->jsonApi()->get($url);

        $response->assertJsonCount(2, 'data');

        $response->assertJsonStructure([
            'links' => ['first', 'last', 'prev', 'next']
        ]);

        /*$response->assertJsonFragment([
            'firts' => route('api.v1.articles.index', ['page[number]' => 1, 'page[size]' => 2]),
            'last' => route('api.v1.articles.index', ['page[number]' => 5, 'page[size]' => 2]),
            'prev' => route('api.v1.articles.index', ['page[number]' => 2, 'page[size]' => 2]),
            'next' => route('api.v1.articles.index', ['page[number]' => 4, 'page[size]' => 2])
        ]);*/
    }
}
