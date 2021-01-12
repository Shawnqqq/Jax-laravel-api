<?php

namespace App\Http\Requests\Api\Admin\Permission;

use Illuminate\Foundation\Http\FormRequest;

class AdministratorRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            "role_ids" => "required|array"
        ];

        if($this->method() === 'POST') {
            $rules += [
                'user_id' => 'required|numeric',
            ];
        }

        return $rules;
    }
}
