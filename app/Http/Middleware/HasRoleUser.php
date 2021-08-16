<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Laratrust\LaratrustFacade;

class HasRoleUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (LaratrustFacade::hasRole('user')) {
            Auth::logout();
            return abort(
                config('laratrust.middleware.handlers.abort.code'),
                __('USER_LOGIN_NOTICE')
            );
        }

        return $next($request);
    }
}
