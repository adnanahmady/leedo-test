<?php

namespace App\Repositories\Categories;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function create(
        string $name,
        string $slug,
        int $ownerId,
    ): Category;

    public function update(
        Category $category,
        string $name,
        string $slug,
    ): Category;
}
