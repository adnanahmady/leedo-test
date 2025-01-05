<?php

namespace App\Http\Resources\Api\V1\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class ArticlesCollection extends ResourceCollection
{
    public function toArray(Request $request): Collection
    {
        return $this->collection->map(
            fn ($article) => new ArticleResource($article)
        );
    }
}
