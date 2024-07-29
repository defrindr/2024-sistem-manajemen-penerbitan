<?php

namespace App\Providers;

use App\Models\Role;
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
        Blade::if('sa', function () {
            $user = auth()->user();

            return $user && $user->roleId === Role::findIdByName(Role::SUPERADMIN);
        });

        Blade::if('admin', function () {
            $user = auth()->user();
            if ($user && $user->roleId === Role::findIdByName(Role::SUPERADMIN)) {
                return true;
            } // bypass SA

            return $user && $user->roleId === Role::findIdByName(Role::ADMINISTRATOR);
        });

        Blade::if('adminreviewer', function () {
            $user = auth()->user();
            if ($user && $user->roleId === Role::findIdByName(Role::SUPERADMIN)) {
                return true;
            } // bypass SA

            return $user && ($user->roleId === Role::findIdByName(Role::ADMINISTRATOR) || $user->roleId === Role::findIdByName(Role::REVIEWER));
        });

        Blade::if('author', function () {
            $user = auth()->user();
            if ($user && $user->roleId === Role::findIdByName(Role::SUPERADMIN)) {
                return true;
            } // bypass SA

            return $user && $user->roleId === Role::findIdByName(Role::AUTHOR);
        });

        Blade::if('reviewer', function () {
            $user = auth()->user();
            if ($user && $user->roleId === Role::findIdByName(Role::SUPERADMIN)) {
                return true;
            } // bypass SA

            return $user && $user->roleId === Role::findIdByName(Role::REVIEWER);
        });
    }
}
