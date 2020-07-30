<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public $allowedSorts = ['title', 'content'];

    protected $fillable = [
    	'title', 'slug', 'content', 'category_id', 'user_id'
    ];

    protected $casts = [
        'id' => 'string'
    ];

    public function user()
    {
    	return $this->belongsTo(App\Models\User::class);
    }

    public function category()
    {
    	return $this->belongsTo(App\Models\Category::class);
    }

    public function scopeTitle(Builder $query, $value)
    {
        return $query->where('title', 'LIKE', "%{$value}%");
    }

    public function scopeContent(Builder $query, $value)
    {
        return $query->where('content', 'LIKE', "%{$value}%");
    }

    public function scopeYear(Builder $query, $value)
    {
        return $query->whereYear('created_at', $value);
    }

    public function scopeMonth(Builder $query, $value)
    {
        return $query->whereMonth('created_at', $value);
    }
}
