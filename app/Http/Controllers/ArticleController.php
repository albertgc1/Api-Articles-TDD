<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleCollection;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::applySorts(request('sort'));

        return ArticleCollection::make($articles->get());
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Article $article)
    {
        return ArticleResource::make($article);
    }

    public function update(Request $request, Article $article)
    {
        //
    }

    public function destroy(Article $article)
    {
        //
    }
}
