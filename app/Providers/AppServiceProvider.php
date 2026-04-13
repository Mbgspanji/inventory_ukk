<?php

namespace App\Providers;

use App\Models\User; // Import ini
use Illuminate\Support\Facades\Gate; // Import ini
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
        // Tambahkan ini
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });
    }
}