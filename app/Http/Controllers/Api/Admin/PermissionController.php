<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission\PermissionTab;
use Auth;

class PermissionController extends Controller
{
    public function index() {
        $tabs = PermissionTab::with([
            'permissionGroups.permissions',
        ])->where('name', 'admin')->first();
        return $this->success($tabs);
    }

    public function my() {
        $info = Auth::user();
        $permissions = Auth::user()
            ->administrator()->first()
            ->allPermissions()
            ->pluck('name')
            ->toArray(); 
        $info -> permissions = $permissions;
        // compact('permissions')
        return $this->success($info);
    }
}
