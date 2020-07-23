<?php

use Illuminate\Support\Facades\Route;

Route::get('articles', 'ArticleController@index')->name('api.v1.articles.index');
Route::get('articles/{article}', 'ArticleController@show')->name('api.v1.articles.show');
