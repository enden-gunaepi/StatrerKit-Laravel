<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('layouts.sidebar', function ($view) {
            $view->with('menus', config('sidebar-data'));
        });
        
        // Menambahkan gate dinamis untuk memeriksa role atau permission
        Gate::define('has-permission', function (User $user, $permission) {
            // Memeriksa apakah user memiliki role dengan ID 1 (Admin)
            if ($user->roles->pluck('id')->contains(1)) {
                return true; // Jika user memiliki role ID 1, maka selalu diizinkan
            }

            // Memeriksa apakah user memiliki permission yang diberikan
            return $user->roles->flatMap->permissions->pluck('name')->contains($permission);
        });
    }
}
