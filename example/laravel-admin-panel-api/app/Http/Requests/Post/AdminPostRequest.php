<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class AdminPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'min:10', 'max:45'],
            'summary' => ['required', 'min:100', 'max:170'],
            'content' => ['required', 'min:3000'],
            'cover' => ['required', 'image', 'mimes:jpg,jpeg,png'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ];
    }

    public function attributes(): array{
        return [
            'category_id' => 'category'
        ];
    }
}