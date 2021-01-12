<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Utils\JWT;
use Illuminate\Support\Facades\Cookie;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // 成功返回封装
    public function success($data = [], $message = 'success')
    {
        return response()->json([
            'error_code' => 0,
            'msg' => $message,
            'data' => $data,
        ]);
    }

    // 失败返回封装
    public function error($code = 1, $message = '', $status = 200)
    {
        return response()->json([
            'error_code' => $code,
            'msg' => $message,
        ], $status);
    }

    // 登录成功封装
    public function loginResponse($prefix, $user, $remember, $redirect = false)
    {
        $expTimes = JWT::expired_mins($remember);
        $token = JWT::login($user, $remember);
        $tCookie = Cookie::make($prefix .'_token', $token, $expTimes, null, null, null, false);
        $uidCookie = Cookie::make($prefix .'_uid', $user->id, $expTimes, null, null, null, false);
        $nicknameCookie = Cookie::make($prefix .'_nickname', $user->nickname, $expTimes, null, null, null, false);
        $avatarCookie = Cookie::make($prefix .'_avatar', $user->avatar_url, $expTimes, null, null, null, false);
        $response = [
            'token' => $token,
            'userInfo' => $user
        ];

        if($redirect) {
            return redirect($redirect)
                ->withCookie($tCookie)
                ->withCookie($uidCookie)
                ->withCookie($nicknameCookie)
                ->withCookie($avatarCookie);
        } else {
            return $this->success($response)
                ->withCookie($tCookie)
                ->withCookie($uidCookie)
                ->withCookie($nicknameCookie)
                ->withCookie($avatarCookie);
        }
    }

    // 退出
    public function logoutResponse($prefix)
    {
        $tCookie = Cookie::forget($prefix . '_token');
        $uidCookie = Cookie::forget($prefix . 'id');
        $nicknameCookie = Cookie::forget($prefix . '_nickname');
        $avatarCookie = Cookie::forget($prefix . '_avatar_url');

        return $this->success()
            ->withCookie($tCookie)
            ->withCookie($uidCookie)
            ->withCookie($nicknameCookie)
            ->withCookie($avatarCookie);
    }
}
