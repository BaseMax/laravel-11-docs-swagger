<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "email"=> "required|email",
            "password" => ["required", Password::min(8)->letters()->numbers()->max(32)->uncompromised()]
        ];
    }
}
