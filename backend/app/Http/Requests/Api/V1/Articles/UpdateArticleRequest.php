<?php

namespace App\Http\Requests\Api\V1\Articles;

use App\Http\Requests\Api\BaseFormRequest;
use App\Models\Article;

class UpdateArticleRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return $this->user()->getKey() === $this->article->writer_id;
    }

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
