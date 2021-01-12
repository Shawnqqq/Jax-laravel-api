<?php

namespace App\Models\Permission;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'sort',
        'group_id',
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
