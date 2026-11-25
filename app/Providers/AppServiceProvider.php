<?php

namespace App\Providers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Angka kecil di menu sidebar panel petugas.
        View::composer('layouts.admin', function ($view) {
            $view->with('menuHitung', [
                'buku' => Buku::count(),
                'anggota' => Anggota::count(),
                'terlambat' => Peminjaman::where('status', 'dipinjam')
                    ->whereDate('tgl_jatuh_tempo', '<', today())
                    ->count(),
            ]);
        });
    }
}
