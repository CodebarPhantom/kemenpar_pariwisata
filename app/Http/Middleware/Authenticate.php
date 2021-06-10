<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Laratrust;

class Authenticate extends Middleware
{
    public function __construct()
    {
        parent::__construct(auth());
        $this->checkPermission();
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    private function checkPermission()
    {
        if (Laratrust::hasRole('user')) {
            Auth::logout();
            return abort(
                config('laratrust.middleware.handlers.abort.code'),
                __('USER_LOGIN_NOTICE')
            );
        }
    }
}
