<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KatalogKategoriTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_kategori_menampilkan_buku_milik_kategori_itu_saja(): void
    {
        $fiksi = Kategori::create(['nama' => 'Fiksi']);
        $sains = Kategori::create(['nama' => 'Sains']);
        Buku::create(['kategori_id' => $fiksi->id, 'kode_buku' => 'BK001', 'judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'tahun_terbit' => 2005, 'stok' => 2]);
        Buku::create(['kategori_id' => $sains->id, 'kode_buku' => 'BK002', 'judul' => 'Fisika Dasar', 'penulis' => 'Halliday', 'tahun_terbit' => 2010, 'stok' => 1]);

        $this->get(route('kategori.show', $fiksi))
            ->assertOk()
            ->assertSee('Laskar Pelangi')
            ->assertDontSee('Fisika Dasar');
    }

    public function test_pencarian_di_halaman_kategori_tetap_dibatasi_kategori(): void
    {
        $fiksi = Kategori::create(['nama' => 'Fiksi']);
        $sains = Kategori::create(['nama' => 'Sains']);
        Buku::create(['kategori_id' => $fiksi->id, 'kode_buku' => 'BK001', 'judul' => 'Dasar Cerita', 'penulis' => 'A', 'tahun_terbit' => 2005, 'stok' => 2]);
        Buku::create(['kategori_id' => $sains->id, 'kode_buku' => 'BK002', 'judul' => 'Dasar Fisika', 'penulis' => 'B', 'tahun_terbit' => 2010, 'stok' => 1]);

        $this->get(route('kategori.show', $fiksi).'?q=Dasar')
            ->assertOk()
            ->assertSee('Dasar Cerita')
            ->assertDontSee('Dasar Fisika');
    }
}
