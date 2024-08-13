<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => "required|string|min:6|max:24",
            "email" => "required|email|unique:users",
            "password" => ["required", "confirmed", Password::min(8)->letters()->numbers()->max(32)->uncompromised()]
        ];
    }
}
