<?php

namespace App\Http\Requests\Api\V1\Categories;

use App\Http\Requests\Api\BaseFormRequest;
use App\Models\Article;

class UpdateCategoryRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return $this->user()->getKey() === $this->category->owner_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'slug' => 'required|string|unique:categories,slug',
        ];
    }
}
