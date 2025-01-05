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
}
