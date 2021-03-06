<?php

namespace App\Http\Requests\Api\Admin\Blog;
use App\Http\Requests\Api\FormRequest;

class BlogRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'content' => 'string',
            'status' => 'numeric',
        ];

        if($this->method() === 'POST' || $this->method() === 'PUT') {
            $rules += [
                'title' => [
                    'required',
                    'max:255',
                    'string'
                ],
                'tag_id' => [
                    'required',
                    'array'
                ],
                'cover_url' => 'required|string',
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'title' => '标题',
            'tag_id' => '标签组',
            'cover_url' => '封面图',
        ];
    }
}
