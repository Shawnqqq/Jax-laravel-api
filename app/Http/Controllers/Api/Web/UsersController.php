<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Web\UserInfoUpdateRequest;
use Auth;
use App\Models\User;

class UsersController extends Controller
{

    public function userInfo()
    {
        $user = Auth::user();
        return $this->success($user);
    }

    public function userInfoUpdate(UserInfoUpdateRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        $user->update($data);
        return $this->success([], '用户信息更新成功');
    }

    public function devLogin() {
        if (!app()->environment('production')) {
            $user = User::find(1);
            return $this->loginResponse('web', $user, true);
        }
        return $this->success();
    }
}
