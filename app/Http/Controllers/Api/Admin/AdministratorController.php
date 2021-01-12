<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission\Role;
use App\Models\User;
use App\Models\Permission\Administrator;
use App\Http\Resources\PaginationCollection;
use App\Http\Requests\Api\Admin\Permission\AdministratorRequest;
use DB;

class AdministratorController extends Controller
{
    // https://laratrust.santigarcor.me/docs/6.x/usage/querying-relationships.html#all-permissions
    public function index(Request $request) {
        $roles = $request->role_name
            ? [$request->role_name]
            : Role::get()->pluck('name')->toArray();

        $admin = Administrator::with('roles')
            ->whereRoleIs($roles)
            ->orderBy('id', 'desc')
            ->paginate($request->input('page_size', 10));
        return new PaginationCollection($admin);
    }

    public function store(AdministratorRequest $request) {
        $admin = Administrator::updateOrCreate([
            'user_id' => $request->user_id
        ]);
        $admin->syncRoles($request->role_ids);
        return $this->success(['id' => $admin->id]);
    }

    public function show($id) {
        $admin = Administrator::with('roles')->findOrFail($id);
        return $this->success($admin);
    }

    public function update(AdministratorRequest $request, $id) {
        $admin = Administrator::findOrFail($id);
        $admin->syncRoles($request->role_ids);
        return $this->success(null);
    }

    public function destroy($id) {
        $admin = Administrator::findOrFail($id);
        $admin->delete();
        return $this->success(null, '移除管理员成功');
    }
}
