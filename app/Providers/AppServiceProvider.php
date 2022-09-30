<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;


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
        //
        Gate::define('admin', function(User $user){
            return $user->role_id === 1;
        });
        
        Gate::define('sub-admin', function(User $user){
            return $user->role_id === 2;
        });

        Gate::define('user', function(User $user){
            return $user->role_id === 3;
        });
    }
}