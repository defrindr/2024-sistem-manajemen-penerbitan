<?php

namespace App\Providers;

use App\Models\Role;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Paginator::useBootstrapFive();

        Blade::if('sa', function () {
            $user = Auth::user();

            return $user && $user->roleId === Role::findIdByName(Role::SUPERADMIN);
        });

        Blade::if('admin', function ($bypass = false) {
            $user = Auth::user();
            if ($bypass && $user && $user->roleId === Role::findIdByName(Role::SUPERADMIN)) {
                return true;
            } // bypass SA

            return $user && $user->roleId === Role::findIdByName(Role::ADMINISTRATOR);
        });

        Blade::if('adminreviewer', function ($bypass = false) {
            $user = Auth::user();
            if ($bypass && $user && $user->roleId === Role::findIdByName(Role::SUPERADMIN)) {
                return true;
            } // bypass SA

            return $user && ($user->roleId === Role::findIdByName(Role::ADMINISTRATOR) || $user->roleId === Role::findIdByName(Role::REVIEWER));
        });

        Blade::if('author', function ($bypass = false) {
            $user = Auth::user();
            if ($bypass && $user && $user->roleId === Role::findIdByName(Role::SUPERADMIN)) {
                return true;
            } // bypass SA

            return $user && $user->roleId === Role::findIdByName(Role::AUTHOR);
        });

        Blade::if('reviewer', function ($bypass = false) {
            $user = Auth::user();
            if ($bypass && $user && $user->roleId === Role::findIdByName(Role::SUPERADMIN)) {
                return true;
            } // bypass SA

            return $user && $user->roleId === Role::findIdByName(Role::REVIEWER);
        });

        Blade::if('forrole', function ($roles = []) {
            $user = Auth::user();

            return in_array($user->roleId, $roles);
        });
    }
}
