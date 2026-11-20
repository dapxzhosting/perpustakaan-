<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PerpustakaanTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_the_required_initial_data(): void
    {
        $this->seed();

        $this->assertTrue(Schema::hasTable('sessions'));
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('kategori', 5);
        $this->assertDatabaseCount('buku', 15);
        $this->assertDatabaseCount('anggota', 8);
        $this->assertDatabaseCount('peminjaman', 6);
        $this->assertTrue(Hash::check('password', User::firstOrFail()->password));
    }

    public function test_admin_pages_require_authentication_and_login_redirects_to_peminjaman(): void
    {
        $this->get(route('admin.buku.index'))
            ->assertRedirect(route('login'));

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('images/book-logo.png')
            ->assertDontSee('✳')
            ->assertDontSee('✦');

        $user = User::factory()->create([
            'email' => 'petugas@sekolah.test',
            'password' => 'password',
        ]);

        $this->post(route('login.store'), [
            'email' => 'petugas@sekolah.test',
            'password' => 'password',
        ])->assertRedirect(route('admin.peminjaman.index'));

        $this->assertAuthenticatedAs($user);
        $this->get(route('admin.peminjaman.index'))
            ->assertOk()
            ->assertDontSee('Pertemuan 1');
    }

    public function test_public_home_does_not_show_internal_project_staging(): void
    {
        Kategori::create(['nama' => 'Sains']);

        $this->get(route('katalog'))
            ->assertOk()
            ->assertSee('images/book-logo.png')
            ->assertSee('Pilih kategori bacaan')
            ->assertDontSee('✳')
            ->assertDontSee('✦')
            ->assertDontSee('Pertemuan 1')
            ->assertDontSee('Pertemuan 2');
    }

    public function test_login_errors_keep_email_and_report_invalid_credentials_in_indonesian(): void
    {
        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => 'salah@sekolah.test',
                'password' => 'salah',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email')
            ->assertSessionHas('_old_input.email', 'salah@sekolah.test');
    }

    public function test_member_registration_requires_login_for_borrowing_and_tracks_own_loans(): void
    {
        $kategori = Kategori::create(['nama' => 'Cerita']);
        $buku = Buku::create([
            'kategori_id' => $kategori->id,
            'kode_buku' => 'CER-001',
            'judul' => 'Cerita Sekolah',
            'penulis' => 'Penulis',
            'tahun_terbit' => now()->year,
            'stok' => 1,
        ]);

        $this->post(route('buku.pinjam', $buku))
            ->assertRedirect(route('login'));
        $this->get(route('buku.show', $buku))
            ->assertOk()
            ->assertSee('Masuk untuk meminjam')
            ->assertSee(route('register', ['buku_id' => $buku->id]));
        $this->get(route('register', ['buku_id' => $buku->id]))
            ->assertOk()
            ->assertSee('Buat akun perpustakaanmu.');

        $this->post(route('register.store'), [
            'nama' => 'Siswa Baru',
            'nis' => '9001',
            'kelas' => 'X IPA 1',
            'no_hp' => '081234567890',
            'email' => 'siswa.baru@sekolah.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'buku_id' => $buku->id,
        ])->assertRedirect(route('buku.show', $buku));

        $user = User::where('email', 'siswa.baru@sekolah.test')->firstOrFail();
        $anggota = Anggota::where('nis', '9001')->firstOrFail();
        $this->assertSame('anggota', $user->role);
        $this->assertSame($user->id, $anggota->user_id);
        $this->assertAuthenticatedAs($user);
        $this->get(route('admin.peminjaman.index'))->assertForbidden();

        $this->post(route('logout'))->assertRedirect(route('katalog'));
        $this->post(route('login.store'), [
            'email' => 'siswa.baru@sekolah.test',
            'password' => 'password123',
            'buku_id' => $buku->id,
        ])->assertRedirect(route('buku.show', $buku));
        $this->assertAuthenticatedAs($user);

        $this->post(route('buku.pinjam', $buku))
            ->assertRedirect(route('akun.peminjaman'));

        $this->assertDatabaseHas('peminjaman', [
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'status' => 'dipinjam',
        ]);
        $this->assertDatabaseHas('buku', ['id' => $buku->id, 'stok' => 0]);
        $this->get(route('akun.peminjaman'))
            ->assertOk()
            ->assertSee('Cerita Sekolah');
    }

    public function test_registration_can_link_an_existing_active_member_with_matching_identity(): void
    {
        $anggota = Anggota::create([
            'nis' => '9002',
            'nama' => 'Siswa Terdaftar',
            'kelas' => 'XI IPA 1',
            'no_hp' => '081234567891',
            'status' => 'aktif',
        ]);

        $this->from(route('register'))
            ->post(route('register.store'), [
                'nama' => 'Siswa Terdaftar',
                'nis' => '9002',
                'kelas' => 'XI IPA 1',
                'no_hp' => '081234567899',
                'email' => 'siswa.terdaftar@sekolah.test',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors('nis');

        $this->post(route('register.store'), [
            'nama' => 'Siswa Terdaftar',
            'nis' => '9002',
            'kelas' => 'XI IPA 1',
            'no_hp' => '081234567891',
            'email' => 'siswa.terdaftar@sekolah.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('katalog'));

        $this->assertDatabaseCount('anggota', 1);
        $this->assertNotNull($anggota->fresh()->user_id);
    }

    public function test_petugas_can_manage_kategori_buku_and_anggota(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $this->assertSame('petugas', $user->role);
        $this->actingAs($user);

        $this->get(route('admin.kategori.index'))->assertOk();
        $this->get(route('admin.kategori.create'))->assertOk();
        $this->get(route('admin.buku.index'))->assertOk();
        $this->get(route('admin.buku.create'))->assertOk();
        $this->get(route('admin.anggota.index'))->assertOk();
        $this->get(route('admin.anggota.create'))->assertOk();

        $this->post(route('admin.kategori.store'), ['nama' => 'Novel'])
            ->assertRedirect(route('admin.kategori.index'));
        $kategori = Kategori::where('nama', 'Novel')->firstOrFail();
        $this->get(route('admin.kategori.edit', $kategori))->assertOk();
        $this->put(route('admin.kategori.update', $kategori), ['nama' => 'Novel Revisi'])
            ->assertRedirect(route('admin.kategori.index'));
        $kategori->refresh();
        $this->assertSame('Novel Revisi', $kategori->nama);

        $this->post(route('admin.buku.store'), [
            'kategori_id' => $kategori->id,
            'kode_buku' => 'NOV-001',
            'judul' => 'Contoh Novel',
            'penulis' => 'Penulis',
            'penerbit' => 'Penerbit',
            'tahun_terbit' => now()->year,
            'stok' => 3,
            'sampul' => UploadedFile::fake()->createWithContent(
                'sampul.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aL5sAAAAASUVORK5CYII=')
            ),
        ])->assertRedirect(route('admin.buku.index'));

        $buku = Buku::where('kode_buku', 'NOV-001')->firstOrFail();
        $sampulLama = $buku->sampul;
        $this->assertDatabaseHas('buku', ['judul' => 'Contoh Novel', 'kategori_id' => $kategori->id]);
        Storage::disk('public')->assertExists($buku->sampul);
        $this->get(route('admin.buku.show', $buku))->assertOk();
        $this->get(route('admin.buku.edit', $buku))->assertOk();

        $this->put(route('admin.buku.update', $buku), [
            'kategori_id' => $kategori->id,
            'kode_buku' => 'NOV-001',
            'judul' => 'Contoh Novel Revisi',
            'penulis' => 'Penulis',
            'penerbit' => 'Penerbit',
            'tahun_terbit' => now()->year,
            'stok' => 2,
            'sampul' => UploadedFile::fake()->createWithContent(
                'sampul-baru.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aL5sAAAAASUVORK5CYII=')
            ),
        ])->assertRedirect(route('admin.buku.index'));
        $this->assertDatabaseHas('buku', ['id' => $buku->id, 'judul' => 'Contoh Novel Revisi']);
        Storage::disk('public')->assertMissing($sampulLama);
        $sampulBaru = $buku->fresh()->sampul;
        Storage::disk('public')->assertExists($sampulBaru);

        $this->post(route('admin.anggota.store'), [
            'nis' => '2001',
            'nama' => 'Siswa Contoh',
            'kelas' => 'X IPA 1',
            'no_hp' => '081234567890',
            'status' => 'aktif',
        ])->assertRedirect(route('admin.anggota.index'));
        $this->assertDatabaseHas('anggota', ['nis' => '2001', 'status' => 'aktif']);
        $anggota = Anggota::where('nis', '2001')->firstOrFail();

        $this->put(route('admin.anggota.update', $anggota), [
            'nis' => '2001',
            'nama' => 'Siswa Contoh Revisi',
            'kelas' => 'X IPA 1',
            'no_hp' => '081234567890',
            'status' => 'nonaktif',
        ])->assertRedirect(route('admin.anggota.index'));
        $this->assertDatabaseHas('anggota', ['id' => $anggota->id, 'nama' => 'Siswa Contoh Revisi', 'status' => 'nonaktif']);

        $this->delete(route('admin.buku.destroy', $buku))
            ->assertRedirect(route('admin.buku.index'));
        Storage::disk('public')->assertMissing($sampulBaru);
        $this->delete(route('admin.kategori.destroy', $kategori))
            ->assertRedirect(route('admin.kategori.index'));
        $this->delete(route('admin.anggota.destroy', $anggota))
            ->assertRedirect(route('admin.anggota.index'));
    }

    public function test_related_records_cannot_be_deleted_and_invalid_phone_is_rejected(): void
    {
        $this->actingAs(User::factory()->create());
        $kategori = Kategori::create(['nama' => 'Sains']);
        $buku = Buku::create([
            'kategori_id' => $kategori->id,
            'kode_buku' => 'SCI-001',
            'judul' => 'Sains',
            'penulis' => 'Penulis',
            'tahun_terbit' => now()->year,
            'stok' => 1,
        ]);
        $anggota = Anggota::create([
            'nis' => '3001',
            'nama' => 'Siswa',
            'kelas' => 'X',
            'no_hp' => '081234567890',
        ]);

        $this->delete(route('admin.kategori.destroy', $kategori))
            ->assertSessionHas('galat');

        $this->post(route('admin.anggota.store'), [
            'nis' => '3002',
            'nama' => 'Siswa Baru',
            'kelas' => 'X',
            'no_hp' => '08123',
            'status' => 'aktif',
        ])->assertSessionHasErrors('no_hp');

        $this->assertDatabaseHas('buku', ['id' => $buku->id]);
        $this->assertDatabaseHas('anggota', ['id' => $anggota->id]);
    }

    public function test_catalog_search_filters_by_category_and_keeps_filters_in_pagination(): void
    {
        $kategori = Kategori::create(['nama' => 'Novel']);
        $lainnya = Kategori::create(['nama' => 'Sains']);

        foreach (range(1, 9) as $number) {
            Buku::create([
                'kategori_id' => $kategori->id,
                'kode_buku' => 'NOV-'.$number,
                'judul' => 'Novel Sekolah '.$number,
                'penulis' => 'Penulis',
                'tahun_terbit' => now()->year,
                'stok' => $number === 1 ? 0 : 2,
            ]);
        }

        Buku::create([
            'kategori_id' => $lainnya->id,
            'kode_buku' => 'SCI-001',
            'judul' => 'Novel di Kategori Lain',
            'penulis' => 'Penulis',
            'tahun_terbit' => now()->year,
            'stok' => 1,
        ]);

        $response = $this->get(route('katalog', ['q' => 'Novel', 'kategori' => $kategori->id]));

        $response->assertOk()
            ->assertViewHas('buku', fn ($buku) => $buku->total() === 9 && $buku->count() === 8)
            ->assertSee('Sedang dipinjam semua')
            ->assertSee('q=Novel', false)
            ->assertSee('kategori='.$kategori->id, false);
    }

    public function test_peminjaman_rejects_inactive_members_empty_stock_and_a_fourth_active_loan(): void
    {
        $this->actingAs(User::factory()->create());
        $kategori = Kategori::create(['nama' => 'Koleksi']);
        $anggota = Anggota::create([
            'nis' => '4001',
            'nama' => 'Siswa Aktif',
            'kelas' => 'X',
            'no_hp' => '081234567890',
            'status' => 'aktif',
        ]);
        $nonaktif = Anggota::create([
            'nis' => '4002',
            'nama' => 'Siswa Nonaktif',
            'kelas' => 'X',
            'no_hp' => '081234567891',
            'status' => 'nonaktif',
        ]);
        $books = collect(range(1, 5))->map(fn (int $number) => Buku::create([
            'kategori_id' => $kategori->id,
            'kode_buku' => 'KOL-'.$number,
            'judul' => 'Buku '.$number,
            'penulis' => 'Penulis',
            'tahun_terbit' => now()->year,
            'stok' => $number === 5 ? 0 : 1,
        ]));

        $this->from(route('admin.peminjaman.create'))
            ->post(route('admin.peminjaman.store'), [
                'anggota_id' => $nonaktif->id,
                'buku_id' => $books[0]->id,
            ])
            ->assertRedirect(route('admin.peminjaman.create'))
            ->assertSessionHasErrors('anggota_id');

        $this->from(route('admin.peminjaman.create'))
            ->post(route('admin.peminjaman.store'), [
                'anggota_id' => $anggota->id,
                'buku_id' => $books[4]->id,
            ])
            ->assertRedirect(route('admin.peminjaman.create'))
            ->assertSessionHasErrors('buku_id');

        foreach ($books->take(3) as $buku) {
            Peminjaman::create([
                'anggota_id' => $anggota->id,
                'buku_id' => $buku->id,
                'tgl_pinjam' => today(),
                'tgl_jatuh_tempo' => today()->addDays(7),
                'status' => 'dipinjam',
            ]);
        }

        $this->from(route('admin.peminjaman.create'))
            ->post(route('admin.peminjaman.store'), [
                'anggota_id' => $anggota->id,
                'buku_id' => $books[3]->id,
            ])
            ->assertRedirect(route('admin.peminjaman.create'))
            ->assertSessionHasErrors('anggota_id');

        $this->assertDatabaseCount('peminjaman', 3);
        $this->assertDatabaseHas('buku', ['id' => $books[4]->id, 'stok' => 0]);
    }

    public function test_successful_peminjaman_sets_dates_and_decreases_stock(): void
    {
        $this->actingAs(User::factory()->create());
        $kategori = Kategori::create(['nama' => 'Fiksi']);
        $anggota = Anggota::create([
            'nis' => '5001',
            'nama' => 'Siswa Pinjam',
            'kelas' => 'XI',
            'no_hp' => '081234567890',
        ]);
        $buku = Buku::create([
            'kategori_id' => $kategori->id,
            'kode_buku' => 'FIK-001',
            'judul' => 'Cerita Sekolah',
            'penulis' => 'Penulis',
            'tahun_terbit' => now()->year,
            'stok' => 2,
        ]);

        $this->post(route('admin.peminjaman.store'), [
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
        ])->assertRedirect(route('admin.peminjaman.index'));

        $pinjaman = Peminjaman::firstOrFail();
        $this->assertSame(today()->toDateString(), $pinjaman->tgl_pinjam->toDateString());
        $this->assertSame(today()->addDays(7)->toDateString(), $pinjaman->tgl_jatuh_tempo->toDateString());
        $this->assertSame('dipinjam', $pinjaman->status);
        $this->assertDatabaseHas('buku', ['id' => $buku->id, 'stok' => 1]);

        $this->from(route('admin.peminjaman.create'))
            ->post(route('admin.peminjaman.store'), [
                'anggota_id' => $anggota->id,
                'buku_id' => $buku->id,
            ])
            ->assertRedirect(route('admin.peminjaman.create'))
            ->assertSessionHasErrors('buku_id');
        $this->assertDatabaseCount('peminjaman', 1);
        $this->assertDatabaseHas('buku', ['id' => $buku->id, 'stok' => 1]);
    }

    public function test_return_calculates_late_fee_and_cannot_increment_stock_twice(): void
    {
        $this->actingAs(User::factory()->create());
        $kategori = Kategori::create(['nama' => 'Sejarah']);
        $anggota = Anggota::create([
            'nis' => '6001',
            'nama' => 'Siswa Kembali',
            'kelas' => 'XII',
            'no_hp' => '081234567890',
        ]);
        $buku = Buku::create([
            'kategori_id' => $kategori->id,
            'kode_buku' => 'SEJ-001',
            'judul' => 'Sejarah Sekolah',
            'penulis' => 'Penulis',
            'tahun_terbit' => now()->year,
            'stok' => 0,
        ]);
        $pinjaman = Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tgl_pinjam' => today()->subDays(10),
            'tgl_jatuh_tempo' => today()->subDays(3),
            'status' => 'dipinjam',
        ]);

        $this->patch(route('admin.peminjaman.kembalikan', $pinjaman))
            ->assertRedirect(route('admin.peminjaman.index'));
        $this->assertDatabaseHas('peminjaman', [
            'id' => $pinjaman->id,
            'status' => 'dikembalikan',
            'denda' => 3000,
        ]);
        $this->assertSame(today()->toDateString(), $pinjaman->fresh()->tgl_kembali->toDateString());
        $this->assertDatabaseHas('buku', ['id' => $buku->id, 'stok' => 1]);

        $this->from(route('admin.peminjaman.index'))
            ->patch(route('admin.peminjaman.kembalikan', $pinjaman))
            ->assertRedirect(route('admin.peminjaman.index'))
            ->assertSessionHasErrors('peminjaman');
        $this->assertDatabaseHas('buku', ['id' => $buku->id, 'stok' => 1]);
    }

    public function test_duplicate_book_code_is_rejected_without_losing_other_form_input(): void
    {
        $this->actingAs(User::factory()->create());
        $kategori = Kategori::create(['nama' => 'Bahasa']);
        Buku::create([
            'kategori_id' => $kategori->id,
            'kode_buku' => 'BAH-001',
            'judul' => 'Buku Lama',
            'penulis' => 'Penulis',
            'tahun_terbit' => now()->year,
            'stok' => 1,
        ]);

        $this->from(route('admin.buku.create'))
            ->post(route('admin.buku.store'), [
                'kategori_id' => $kategori->id,
                'kode_buku' => 'BAH-001',
                'judul' => 'Buku Baru',
                'penulis' => 'Penulis Baru',
                'tahun_terbit' => now()->year,
                'stok' => 2,
            ])
            ->assertRedirect(route('admin.buku.create'))
            ->assertSessionHasErrors('kode_buku')
            ->assertSessionHas('_old_input.judul', 'Buku Baru');
    }

    public function test_petugas_can_add_a_cover_when_editing_a_book_without_one(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());
        $kategori = Kategori::create(['nama' => 'Pengetahuan']);
        $buku = Buku::create([
            'kategori_id' => $kategori->id,
            'kode_buku' => 'PEN-001',
            'judul' => 'Belajar Bersama',
            'penulis' => 'Penulis',
            'tahun_terbit' => now()->year,
            'stok' => 2,
        ]);

        $this->put(route('admin.buku.update', $buku), [
            'kategori_id' => $kategori->id,
            'kode_buku' => 'PEN-001',
            'judul' => 'Belajar Bersama',
            'penulis' => 'Penulis',
            'tahun_terbit' => now()->year,
            'stok' => 2,
            'sampul' => UploadedFile::fake()->createWithContent(
                'sampul.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aL5sAAAAASUVORK5CYII=')
            ),
        ])->assertRedirect(route('admin.buku.index'));

        $sampul = $buku->fresh()->sampul;
        $this->assertNotNull($sampul);
        Storage::disk('public')->assertExists($sampul);
    }
}
