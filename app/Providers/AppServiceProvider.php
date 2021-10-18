<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB, Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function($view) {

            $view->with('authUser',DB::table('users')->select('users.id', 'users.user_type', 'ti.name as tourism_name', 'ti.balance')
            ->leftJoin('tourism_infos as ti', 'ti.id', '=', 'users.tourism_info_id')
            ->where('users.id', optional(Auth::user())->id)
            ->first());
        });

    }
}
