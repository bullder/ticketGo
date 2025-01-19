<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListPostsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'author_id' => ['nullable', 'integer', 'exists:authors,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
