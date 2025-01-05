<?php

namespace App\Repositories\Categories;

use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function create(
        string $name,
        string $slug,
        int $ownerId,
    ): Category {
        return Category::create([
            'name' => $name,
            'slug' => $slug,
            'owner_id' => $ownerId,
        ]);
    }
}
