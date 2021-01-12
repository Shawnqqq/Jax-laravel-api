<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Web\Auth\AuthEmailLoginRequest;
use App\Http\Requests\Api\Web\Auth\AuthEmailRegisterRequest;
use App\Models\User;
use EasyWeChat\Factory;
use Carbon\Carbon;

class AuthController extends Controller
{
    // 网页应用
    public function wechatCallback(Request $request)
    {
        $config = config('wechat.web');
        $app = Factory::officialAccount($config);
        $authUser = $app->oauth->user();
        $data = [
            "unionid" => $authUser->original['unionid'],
            "nickname" => $authUser->nickname,
            "avatar_url" => $authUser->avatar,
            "gender" => $authUser->original['sex'],
            "visited_at" => Carbon::now()
        ];

        $user = User::where('unionid', $authUser->original['unionid'])->first();
        if($user) {
            $user->update([ 'visited_at' => Carbon::now()]);
        }else {
            $user =  User::create($data);
        }

        $redirect_uri = $request->redirect_uri ?: config('wechat.web.redirect_uri');
        return $this->loginResponse('x', $user, 1, $redirect_uri);
    }

    public function emialRegister(AuthEmailRegisterRequest $request) {
        $user = User::create([
           'email' => $request->email,
           'password' => bcrypt($request->password),
        ]);
        return $this->loginResponse('x', $user, 1);
    }

    public function emailLogin(AuthEmailLoginRequest $request) {
        $user = User::where('email', $request->email)->first();
        $flag = app('hash')->check($request->password, $user->password);
        if (!$flag) {
            return $this->error(1, '密码错误，请重新尝试');
        }
        return $this->loginResponse('x', $user, 1);
    }

    public function logout() {
        return $this->logoutResponse('x');
    }
}
