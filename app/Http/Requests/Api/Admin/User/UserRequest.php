<?php

namespace App\Http\Requests\Api\Admin\User;

use App\Http\Requests\Api\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'nickname' => 'string',
            'avatar_url' => 'string',
            'gender' => 'numeric|in:0,1,2',
            'birthday' => 'nullable|date_format:Y-m-d',
            'introduction' => 'nullable|max:500',
            'province' => 'nullable|string',
            'city' => 'nullable|string',
            'district' => 'nullable|string',
         ];

        if($this->method() === 'POST') {
            $rules += [
                'phone' => [
                    'required',
                    'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$/',
                    'unique:users,phone'
                ],
            ];
        }

        return $rules;

    }
}
