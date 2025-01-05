<?php

namespace App\Http\Requests\Api\V1\Articles;

use App\Http\Requests\Api\BaseFormRequest;

class StoreArticleRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'slug' => 'required|string|unique:articles,slug',
            'content' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
        ];
    }
}
