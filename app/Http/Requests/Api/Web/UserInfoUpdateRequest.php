<?php

namespace App\Http\Requests\Api\Web;

use App\Http\Requests\Api\FormRequest;

class UserInfoUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
           'nickname' => 'string',
           'avatar_url' => 'string',
           'gender' => 'numeric|in:0,1,2',
           'birthday' => 'nullable|date_format:Y-m-d',
           'introduction' => 'nullable|max:500',
           'province' => 'nullable|string',
           'city' => 'nullable|string',
           'district' => 'nullable|string',
        ];
    }
}
