<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission = null)
    {
        if (Auth::guard('web')->guest()) {
            return response('Unauthorized.', 401);
        }
        $hasPermission = Auth::user()
            ->administrator()->first()
            ->hasPermission($permission);

        if(!$hasPermission) {
            return response('Unauthorized.', 403);
        }

        return $next($request);
    }
}
