<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Gate::define('manage-library', fn (User $user): bool => $user->role === 'petugas');
        Gate::define('member-account', fn (User $user): bool => $user->role === 'anggota');
    }
}
