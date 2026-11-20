<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = new User([
            'name' => 'Petugas Perpustakaan',
            'email' => 'petugas@sekolah.test',
            'password' => Hash::make('password'),
        ]);
        $petugas->role = 'petugas';
        $petugas->save();

        $kategori = collect(['Fiksi', 'Sains', 'Sejarah', 'Bahasa', 'Teknologi'])
            ->mapWithKeys(fn (string $nama) => [$nama => Kategori::create(['nama' => $nama])]);

        $dataBuku = [
            ['BK001', 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, 0, 'Fiksi'],
            ['BK002', 'Bumi', 'Tere Liye', 'Gramedia', 2014, 0, 'Fiksi'],
            ['BK003', 'Negeri 5 Menara', 'Ahmad Fuadi', 'Gramedia', 2009, 3, 'Fiksi'],
            ['BK004', 'Sapiens', 'Yuval Noah Harari', 'Kepustakaan Populer Gramedia', 2011, 2, 'Sejarah'],
            ['BK005', 'Sejarah Indonesia', 'Sardiman', 'Yudhistira', 2017, 4, 'Sejarah'],
            ['BK006', 'Fisika Dasar', 'Halliday Resnick', 'Erlangga', 2014, 2, 'Sains'],
            ['BK007', 'Ensiklopedia Sains', 'Tim Pustaka', 'Pustaka Baru', 2020, 3, 'Sains'],
            ['BK008', 'Matematika SMA', 'Sukino', 'Erlangga', 2016, 2, 'Sains'],
            ['BK009', 'Kamus Bahasa Indonesia', 'Badan Bahasa', 'Kemdikbud', 2019, 5, 'Bahasa'],
            ['BK010', 'English Grammar', 'Betty Azar', 'Pearson', 2016, 2, 'Bahasa'],
            ['BK011', 'Pemrograman Web', 'Abdul Kadir', 'Andi', 2018, 3, 'Teknologi'],
            ['BK012', 'Dasar-Dasar Komputer', 'Abdul Kadir', 'Andi', 2015, 2, 'Teknologi'],
            ['BK013', 'Algoritma dan Pemrograman', 'Rinaldi Munir', 'Informatika', 2016, 3, 'Teknologi'],
            ['BK014', 'Sang Pemimpi', 'Andrea Hirata', 'Bentang Pustaka', 2006, 2, 'Fiksi'],
            ['BK015', 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Lentera Dipantara', 1980, 2, 'Sejarah'],
        ];

        $buku = collect($dataBuku)->map(function (array $item) use ($kategori) {
            return Buku::create([
                'kode_buku' => $item[0],
                'judul' => $item[1],
                'penulis' => $item[2],
                'penerbit' => $item[3],
                'tahun_terbit' => $item[4],
                'stok' => $item[5],
                'kategori_id' => $kategori[$item[6]]->id,
            ]);
        });

        $dataAnggota = [
            ['1001', 'Alya Putri', 'X IPA 1', '081234567801', 'aktif'],
            ['1002', 'Bima Pratama', 'X IPA 2', '081234567802', 'aktif'],
            ['1003', 'Citra Lestari', 'XI IPA 1', '081234567803', 'aktif'],
            ['1004', 'Dimas Saputra', 'XI IPS 1', '081234567804', 'aktif'],
            ['1005', 'Eka Wulandari', 'XII IPA 1', '081234567805', 'aktif'],
            ['1006', 'Fajar Nugraha', 'XII IPS 1', '081234567806', 'aktif'],
            ['1007', 'Gita Maharani', 'X IPA 3', '081234567807', 'aktif'],
            ['1008', 'Hadi Kurniawan', 'XI IPA 2', '081234567808', 'nonaktif'],
        ];

        $anggota = collect($dataAnggota)->map(fn (array $item) => Anggota::create([
            'nis' => $item[0],
            'nama' => $item[1],
            'kelas' => $item[2],
            'no_hp' => $item[3],
            'status' => $item[4],
        ]));

        $riwayat = [
            [0, 0, -10, null, 'dipinjam', 0],
            [1, 1, -3, null, 'dipinjam', 0],
            [2, 2, -7, 0, 'dikembalikan', 0],
            [3, 3, -10, -3, 'dikembalikan', 3000],
            [4, 4, -7, -6, 'dikembalikan', 0],
            [5, 5, -12, -3, 'dikembalikan', 2000],
        ];

        foreach ($riwayat as [$anggotaIndex, $bukuIndex, $pinjamOffset, $kembaliOffset, $status, $denda]) {
            $tglPinjam = now()->addDays($pinjamOffset);

            Peminjaman::create([
                'anggota_id' => $anggota[$anggotaIndex]->id,
                'buku_id' => $buku[$bukuIndex]->id,
                'tgl_pinjam' => $tglPinjam->toDateString(),
                'tgl_jatuh_tempo' => $tglPinjam->copy()->addDays(7)->toDateString(),
                'tgl_kembali' => $kembaliOffset === null ? null : now()->addDays($kembaliOffset)->toDateString(),
                'status' => $status,
                'denda' => $denda,
            ]);
        }
    }
}
