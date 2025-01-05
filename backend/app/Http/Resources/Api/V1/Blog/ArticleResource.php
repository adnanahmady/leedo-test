<?php

namespace App\Http\Resources\Api\V1\Blog;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /** @var Article */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'writer' => new WriterResource($this->writer),
            'category' => new CategoryResource($this->category),
        ];
    }
}
