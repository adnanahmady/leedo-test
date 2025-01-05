<?php

namespace App\Http\Requests\Api\V1\Categories;

use App\Http\Requests\Api\BaseFormRequest;

class StoreCategoryRequest extends BaseFormRequest
{
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
