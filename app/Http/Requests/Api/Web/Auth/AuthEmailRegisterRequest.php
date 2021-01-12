<?php

namespace App\Http\Requests\Api\Web\Auth;

use App\Http\Requests\Api\FormRequest;

class AuthEmailRegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6',
        ];
    }
}
