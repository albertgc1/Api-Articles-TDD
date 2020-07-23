<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'articles',
            'id' => (string) $this->id,
            'attributes' => [
                'title' => $this->title,
                'slug' => $this->slug,
                'content' => $this->content,
            ],
            'links' => [
                'self' => route('api.v1.articles.show', $this->getRouteKey())
            ]
        ];
    }
}
