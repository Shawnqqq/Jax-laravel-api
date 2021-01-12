<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
class FormRequest extends BaseFormRequest
{
    public function failedValidation(Validator $validator): void
     {
         $error = $validator->errors()->all();
         if (app()->environment() != 'production') {
             \Log::info('422 数据：',request()->all());
             \Log::error('422 异常日志：', ['data' => null, 'msg' => $error[0], 'error_code' => 1]);
         }

         throw new HttpResponseException(response()->json([
             'error_code' => 1,
             'msg' => $error[0],
             'data' => null,
         ], 200));
     }
}
