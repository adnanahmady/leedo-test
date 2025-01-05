<?php

namespace App\Repositories\Categories;

use App\Models\Article;
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

    public function update(
        Category $category,
        string $name,
        string $slug,
    ): Category {
        $category->update([
            'name' => $name,
            'slug' => $slug,
        ]);

        return $category->refresh();
    }
}
