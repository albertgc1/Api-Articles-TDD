<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
    	'title', 'slug', 'content', 'category_id', 'user_id'
    ];

    public function user()
    {
    	return $this->belongsTo(App\Models\User::class);
    }

    public function category()
    {
    	return $this->belongsTo(App\Models\Category::class);
    }
}
