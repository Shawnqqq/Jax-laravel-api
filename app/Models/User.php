<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
        'unionid', 'session_key',
        'nickname', 'realname', 'phone',
        'country', 'province', 'city', 'district',
        'gender', 'avatar_url',
        'birthday', 'introduction',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'visited_at',
        'password',
    ];

    public function administrator()
    {
        return $this->hasOne('App\Models\Permission\Administrator');
    }
}
