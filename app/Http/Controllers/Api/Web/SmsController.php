<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use Illuminate\Support\Str;
use App\Http\Requests\Api\Web\VerificationCodeRequest;
use App\Http\Requests\Api\Web\VerificationPhoneRequest;
use App\Models\User;

class SmsController extends Controller
{
    public function registerCode(VerificationCodeRequest $request, EasySms $easySms) {
        $phone = $request->phone;

        // if (!app()->environment('production')) {
        if (true) {
            $code = '1234';
        } else {
            // 生成4位随机数，左侧补0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

            try {
                $template = config('easysms.gateways.aliyun.templates.register');
                $result = $easySms->send($phone, [
                    'template' => $template,
                    'data' => [
                        'code' => $code
                    ],
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('aliyun')->getMessage();
                abort(500, $message ?: '短信发送异常');
            }
        }

        $expired = 5;
        $key = 'verificationCode_'.Str::random(15);
        $expiredAt = now()->addMinutes($expired);
        // 缓存验证码 5 分钟过期。
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);
        return $this->success([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ], '验证码发送成功, 有效时间 '. $expired .' 分钟。');
    }

    public function registerPhone(VerificationPhoneRequest $request) {
        $checkMessage = $this->verificationCode($request);
        if($checkMessage) {
            return $this->error(1, $checkMessage);
        }
        $user =  User::updateOrCreate(['phone' => $request->phone]);
        return $this->loginResponse('x', $user, 1);
    }

    private function verificationCode($request) {
        $key = $request->key;
        $code = $request->code;
        $phone = $request->phone;
        if(!$key) {
            return '请输入 key';
        }
        if(!$key) {
            return '请输入 code';
        }
        if(!$phone) {
            return '请输入手机号';
        }
        $verifyData = \Cache::get($key);
        if (!$verifyData) {
           return '验证码已失效';
        }

        if (!hash_equals($verifyData['phone'], $phone)) {
            return '手机号码不匹配';
        }

        if (!hash_equals($verifyData['code'], $code)) {
            return '验证码错误';
        }
    }
}
