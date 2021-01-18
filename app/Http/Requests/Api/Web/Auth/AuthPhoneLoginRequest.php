<?php

namespace App\Http\Requests\Api\Web\Auth;

use App\Http\Requests\Api\FormRequest;

class AuthPhoneLoginRequest extends FormRequest
{
    public function rules()
    {
        return [
          'phone' => [
            'required',
            'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$/',
        ],
            'password' => 'required|min:6',
        ];
    }
}