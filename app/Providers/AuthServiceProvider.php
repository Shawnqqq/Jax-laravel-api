<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Auth;
use Illuminate\Support\Str;
use App\Utils\JWT;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Auth::viaRequest('token', function ($request) {
            $jwt = ($token = $request->header('Authorization'))
                ? Str::replaceFirst('Bearer ', '', $token)
                : $request->cookie('token');

            $decode = Jwt::decode($jwt);

            if (! $decode) {
                return null;
            }

            return User::find($decode->sub);
        });
    }
}
