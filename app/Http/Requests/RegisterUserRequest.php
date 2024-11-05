<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->role->name === 'administrator';
    }

    public function rules()
    {
        return [
            'username' => 'required|string|max:50|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ];
    }
}
