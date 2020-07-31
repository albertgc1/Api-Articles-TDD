<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FilterArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_filter_articles_by_title()
    {
        factory(Article::class)->create(['title' => 'Aprende Laravel']);
        factory(Article::class)->create(['title' => 'Otro title']);

        $response = $this->getJson(route('api.v1.articles.index', ['filter[title]' => 'Laravel']));

        $response->assertJsonCount(1, 'data')
                ->assertSee('Aprende Laravel')
                ->assertDontSee('Otro title');
    }

    /** @test */
    public function can_filter_articles_by_content()
    {
        factory(Article::class)->create([
            'title' => 'Aprende Laravel',
            'content' => 'Laravel Content'
        ]);
        factory(Article::class)->create([
            'title' => 'Otro title',
            'content' => 'Other Content'
        ]);

        $response = $this->getJson(route('api.v1.articles.index', ['filter[content]' => 'Laravel']));

        $response->assertJsonCount(1, 'data')
                ->assertSee('Aprende Laravel')
                ->assertDontSee('Otro title');
    }

    /** @test */
    public function can_filter_articles_by_year()
    {
        factory(Article::class)->create([
            'title' => 'Aprende Laravel 2020',
            'created_at' => now()->year(2020)
        ]);
        factory(Article::class)->create([
            'title' => 'Aprende Laravel 2019',
            'created_at' => now()->year(2019)
        ]);

        $response = $this->getJson(route('api.v1.articles.index', ['filter[year]' => 2020]));

        $response->assertJsonCount(1, 'data')
                ->assertSee('Aprende Laravel 2020')
                ->assertDontSee('Aprende Laravel 2019');
    }

    /** @test */
    public function can_filter_articles_by_month()
    {
        factory(Article::class)->create([
            'title' => 'Aprende Laravel February',
            'created_at' => now()->month(1)
        ]);
        factory(Article::class)->create([
            'title' => 'Aprende Laravel may',
            'created_at' => now()->month(5)
        ]);

        $response = $this->getJson(route('api.v1.articles.index', ['filter[month]' => 1]));

        $response->assertJsonCount(1, 'data')
                ->assertSee('Aprende Laravel February')
                ->assertDontSee('Aprende Laravel may');
    }

    /** @test */
    public function cannon_filter_articles_by_unknow_fields()
    {
        factory(Article::class)->create();

        $response = $this->getJson(route('api.v1.articles.index', ['filter[unknow]' => 4]));

        $response->assertStatus(400);
    }

     /** @test */
    public function can_filter_articles_by_title_and_content()
    {
        factory(Article::class)->create([
            'title' => 'Aprende Laravel',
            'content' => 'Content'
        ]);
        factory(Article::class)->create([
            'title' => 'title 2',
            'content' => 'Other Laravel'
        ]);
        factory(Article::class)->create([
            'title' => 'Otro',
            'content' => 'Other content'
        ]);

        $response = $this->getJson(route('api.v1.articles.index', ['filter[search]' => 'Laravel']));

        $response->assertJsonCount(2, 'data')
                ->assertSee('Aprende Laravel')
                ->assertSee('title 2')
                ->assertDontSee('Otro');
    }

     /** @test */
    public function can_filter_articles_by_one_more_words_by_title_and_content()
    {
        factory(Article::class)->create([
            'title' => 'Aprende Laravel',
            'content' => 'Content'
        ]);
        factory(Article::class)->create([
            'title' => 'Aprende',
            'content' => 'Other Laravel'
        ]);
        factory(Article::class)->create([
            'title' => 'Otro',
            'content' => 'Other content'
        ]);

        $response = $this->getJson(route('api.v1.articles.index', ['filter[search]' => 'Aprende Laravel']));

        $response->assertJsonCount(2, 'data')
                ->assertSee('Aprende Laravel')
                ->assertSee('Aprende')
                ->assertDontSee('Otro');
    }
}
