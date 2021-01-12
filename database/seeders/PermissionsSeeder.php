<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

use App\Models\Permission\Permission;
use App\Models\Permission\PermissionTab;
use App\Models\Permission\PermissionGroup;
use App\Models\Permission\Role;
use App\Models\Permission\RoleUser;
use App\Models\Permission\PermissionRole;

class PermissionsSeeder extends Seeder
{
    public $tabs = [
        [
            'name' => 'admin',
            'display_name' => '管理中心',
            'groups' => [
                [
                    'name' => 'common-manage',
                    'display_name' => '通用管理',
                    'permissions' => [
                        ['name' => 'dashboard-manage', 'display_name' => '概览'],
                        ['name' => 'qiniu-manage', 'display_name' => '七牛'],
                    ],
                ],
                [
                    'name' => 'blog-manage',
                    'display_name' => '博客管理',
                    'permissions' => [
                        ['name' => 'blog-manage', 'display_name' => '博客']
                    ],
                ],
                [
                    'name' => 'user-manage',
                    'display_name' => '用户管理',
                    'permissions' => [
                        ['name' => 'user-manage', 'display_name' => '用户'],
                    ],
                ],
                [
                    'name' => 'company-manage',
                    'display_name' => '管理员管理',
                    'permissions' => [
                        ['name' => 'permission-manage', 'display_name' => '管理员权限'],
                    ],
                ],
            ],
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role = Role::updateOrCreate([ 'name' => 'admin'], [
            'display_name' => '管理员',
        ]);

        foreach ($this->tabs as $tabIndex => $tabInfo) {
            $tab = PermissionTab::updateOrCreate([
                'name' => $tabInfo['name'],
            ], [
                'display_name' => $tabInfo['display_name'],
                'sort' => $tabIndex + 1,
            ]);


            foreach ($tabInfo['groups'] as $groupIndex => $groupInfo) {
                $group = PermissionGroup::updateOrCreate([
                    'name' => $groupInfo['name'],
                ], [
                    'tab_id' => $tab->id,
                    'display_name' => $groupInfo['display_name'],
                    'sort' => $groupIndex + 1,
                ]);


                foreach ($groupInfo['permissions'] as $permissionKey => $permissionInfo) {
                    $permission = Permission::updateOrCreate([
                        'name' => $permissionInfo['name'],
                    ], [
                        'display_name' => $permissionInfo['display_name'],
                        'sort' => $permissionKey + 1,
                        'group_id' => $group->id,
                    ]);
                    PermissionRole::updateOrCreate([
                        'permission_id' => $permission->id,
                        'role_id' => $role->id
                    ]);
                }
            }
        }
    }
}
