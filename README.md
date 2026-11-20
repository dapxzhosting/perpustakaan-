# Sistem Informasi Perpustakaan Sekolah

Aplikasi Laravel untuk pengelolaan perpustakaan sekolah. Aplikasi mencakup target **Pertemuan 1 dan 2**: login petugas, CRUD kategori/buku/anggota, katalog publik, transaksi peminjaman dan pengembalian, denda, serta riwayat.

## Menjalankan aplikasi

1. Pastikan PHP 8.2+, Composer, dan MySQL/MariaDB tersedia.
2. Gunakan konfigurasi MySQL yang sudah ada di `.env` dan pastikan databasenya sudah dibuat. Jangan menyalin `.env.example` di atas konfigurasi lokal Anda.
3. Jika `APP_KEY` belum pernah dibuat, jalankan `php artisan key:generate`.
4. Jalankan:

   ```bash
   composer install
   php artisan migrate --seed
   php artisan storage:link
   php artisan serve
   ```

5. Buka `http://127.0.0.1:8000`.

> `php artisan migrate --seed` memakai koneksi pada `.env` dan menambahkan data awal. Untuk memeriksa instalasi dari awal, jalankan `php artisan migrate:fresh --seed` hanya pada database pengembangan yang boleh dikosongkan karena perintah tersebut menghapus semua tabel.

## Akun awal

- Email: `petugas@sekolah.test`
- Kata sandi: `password`

Ganti kata sandi akun awal sebelum aplikasi digunakan di luar lingkungan latihan.

## Fitur

- Login dan logout petugas; seluruh halaman `/admin/*` dilindungi middleware autentikasi.
- CRUD kategori dengan pencegahan penghapusan kategori yang masih memiliki buku.
- CRUD buku dengan validasi, pagination, unggah sampul JPG/PNG maksimal 2 MB, dan pembersihan file sampul lama saat diganti.
- CRUD anggota dengan validasi NIS, nomor HP, dan status anggota.
- Seeder berisi akun petugas, 5 kategori, 15 buku, 8 anggota, dan 6 contoh riwayat peminjaman.
- Katalog publik dengan detail buku, pencarian judul/penulis/kode, filter kategori, dan pagination 8 buku per halaman.
- Peminjaman dengan validasi anggota aktif, ketersediaan stok, maksimal 3 pinjaman aktif, dan larangan meminjam ulang judul yang belum dikembalikan.
- Pengembalian transaksional yang menambah stok dan menghitung denda Rp1.000 per hari keterlambatan.
- Riwayat transaksi dengan filter status (termasuk keterlambatan) dan pencarian nama anggota.

Jalankan pengujian dengan `php artisan test`.
