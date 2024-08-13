<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'min:10', 'max:45'],
            'summary' => ['required', 'min:100', 'max:200'],
            'content' => ['required', 'min:3000'],
            'cover' => ['required', 'image', 'mimes:jpg,jpeg,png'],
            'author_id' => ['required', 'integer', 'exists:users,id'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
