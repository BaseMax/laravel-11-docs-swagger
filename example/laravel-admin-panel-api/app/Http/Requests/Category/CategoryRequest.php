<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:4'],
            // 'user_id' => ['required', 'exists:users.id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
