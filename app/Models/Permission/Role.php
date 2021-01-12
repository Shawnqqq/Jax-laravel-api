<?php

namespace App\Models\Permission;

use Cache;
use Laratrust\Models\LaratrustRole;
use App\Models\Permission\Administrator;


class Role extends LaratrustRole
{

    protected $perPage = 50;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    public function administrators()
    {
        return $this->belongsToMany(Administrator::class, 'role_user', 'role_id', 'user_id');
    }

    public function flushCache()
    {
        Cache::forget('laratrust_permissions_for_role_'.$this->getKey());
    }

    protected $hidden = [
        'created_at', 'updated_at'
    ];

}
