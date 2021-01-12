<?php

namespace App\Http\Requests\Api\Web\Auth;

use App\Http\Requests\Api\FormRequest;

class AuthEmailLoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
        ];
    }
}
