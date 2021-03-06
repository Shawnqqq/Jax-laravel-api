<?php

namespace App\Http\Requests\Api\Web;
use App\Http\Requests\Api\FormRequest;

class VerificationCodeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => [
                'required',
                'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$/',
            ]
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => '手机号格式不对',
            'phone.required' => '手机号不能为空。',
            'phone.unique' => '手机号已存在',
        ];
    }
}
