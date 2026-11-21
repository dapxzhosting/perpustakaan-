<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Memastikan aplikasi tidak melebihi rancangan jobsheet Tugas 1:
 * skema database dan daftar route hanya yang diminta.
 */
class SesuaiJobsheetTest extends TestCase
{
    use RefreshDatabase;

    public function test_skema_tabel_sesuai_rancangan_jobsheet(): void
    {
        $this->assertEqualsCanonicalizing(
            ['id', 'name', 'email', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at'],
            Schema::getColumnListing('users')
        );
        $this->assertEqualsCanonicalizing(
            ['id', 'nis', 'nama', 'kelas', 'no_hp', 'status', 'created_at', 'updated_at'],
            Schema::getColumnListing('anggota')
        );
    }

    public function test_route_tambahan_di_luar_jobsheet_tidak_ada(): void
    {
        foreach (['register', 'register.store', 'akun.peminjaman', 'buku.pinjam', 'kategori.show'] as $nama) {
            $this->assertFalse(Route::has($nama), "Route {$nama} tidak ada di jobsheet.");
        }

        $this->get('/daftar')->assertNotFound();
        $this->get('/kategori/1')->assertNotFound();
        $this->get('/akun/peminjaman')->assertNotFound();
    }

    public function test_route_wajib_jobsheet_tersedia(): void
    {
        foreach ([
            'katalog', 'buku.show', 'login', 'logout',
            'admin.peminjaman.index', 'admin.peminjaman.create', 'admin.peminjaman.store', 'admin.peminjaman.kembalikan',
            'admin.kategori.index', 'admin.buku.index', 'admin.buku.show', 'admin.anggota.index',
        ] as $nama) {
            $this->assertTrue(Route::has($nama), "Route {$nama} harus ada.");
        }
    }
}
