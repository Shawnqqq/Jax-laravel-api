<?php

namespace App\Http\Requests\Api\Admin\Blog;
use App\Http\Requests\Api\FormRequest;

class BlogTagsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'sort' => 'array',
            'status' => 'numeric',
        ];

        if($this->method() === 'POST' || $this->method() === 'PUT') {
            $rules += [
                'name' => [
                    'required',
                    'max:255',
                    'string'
                ],
                'group_id' => [
                    'required',
                    'numeric'
                ]
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => '名称',
            'sort' => '排序'
        ];
    }
}
