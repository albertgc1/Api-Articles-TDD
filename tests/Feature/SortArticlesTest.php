<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SortArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_sort_articles_by_title_asc()
    {
        factory(Article::class)->create(['title' => 'C title']);
        factory(Article::class)->create(['title' => 'B title']);
        factory(Article::class)->create(['title' => 'A title']);

        $url = route('api.v1.articles.index', ['sort' => 'title']);
        $this->jsonApi()->get($url)->assertSeeInOrder([
            'A title',
            'B title',
            'C title'
        ]);
    }

    /** @test */
    public function it_can_sort_articles_by_title_desc()
    {
        factory(Article::class)->create(['title' => 'C title']);
        factory(Article::class)->create(['title' => 'B title']);
        factory(Article::class)->create(['title' => 'A title']);

        $url = route('api.v1.articles.index', ['sort' => '-title']);
        $this->jsonApi()->get($url)->assertSeeInOrder([
            'C title',
            'B title',
            'A title'
        ]);
    }

    /** @test */
    public function it_can_sort_articles_by_title_and_content_asc()
    {
        factory(Article::class)->create([
            'title' => 'C title',
            'content' => 'B content'
        ]);
        factory(Article::class)->create([
            'title' => 'A title',
            'content' => 'C content'
        ]);
        factory(Article::class)->create([
            'title' => 'B title',
            'content' => 'D content'
        ]);

        $url = route('api.v1.articles.index').'?sort=title,-content';
        $this->jsonApi()->get($url)->assertSeeInOrder([
            'A title',
            'B title',
            'C title'
        ]);
    }

    /** @test */
    public function it_can_sort_articles_by_title_and_content_desc()
    {
        factory(Article::class)->create([
            'title' => 'C title',
            'content' => 'B content'
        ]);
        factory(Article::class)->create([
            'title' => 'A title',
            'content' => 'C content'
        ]);
        factory(Article::class)->create([
            'title' => 'B title',
            'content' => 'D content'
        ]);

        $url = route('api.v1.articles.index').'?sort=-content,title';
        $this->jsonApi()->get($url)->assertSeeInOrder([
            'D content',
            'C content',
            'B content'
        ]);
    }


    /** @test */
    public function it_cannon_sort_articles_by_unknow_fields()
    {
        factory(Article::class)->times(3)->create();

        $url = route('api.v1.articles.index').'?sort=unknow';
        $this->jsonApi()->get($url)->assertStatus(400);
    }
}
