<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Admin\User\UserRequest;
use App\Http\Resources\PaginationCollection;

class UserController extends Controller
{
    public function index(UserRequest $request) {
        $data = $request->validated();
        $phone = $request->phone;
        $users = User::where($data)
            ->orWhere('phone','like','%'.$phone.'%')
            ->orderBy('id', 'desc')
            ->paginate($request->input('page_size', 10));
        return new PaginationCollection($users);
    }

    public function show(UserRequest $request, $id) {
        $user = User::findOrFail($id);
        return $this->success($user);
    }

    public function update(UserRequest $request, $id) {
        $data = $request->validated();
        $user = User::findOrFail($id);
        $user->update($data);
        return $this->success([],'用户信息更新成功');
    }

    public function store(UserRequest $request) {
        $data = $request->validated();
        $user = User::create($data);
        return $this->success(['id'=>$user->id], '用户创建成功');
    }
}
