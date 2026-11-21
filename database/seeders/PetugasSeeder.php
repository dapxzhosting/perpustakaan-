<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('PETUGAS_EMAIL') ?: getenv('PETUGAS_EMAIL');
        $password = env('PETUGAS_PASSWORD') ?: getenv('PETUGAS_PASSWORD');
        $nama = env('PETUGAS_NAMA') ?: getenv('PETUGAS_NAMA') ?: 'Petugas Perpustakaan';

        if (! $email || ! $password) {
            $this->command?->error('Isi variabel PETUGAS_EMAIL dan PETUGAS_PASSWORD terlebih dahulu.');

            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            ['name' => $nama, 'password' => Hash::make($password)]
        );

        $this->command?->info("Akun petugas {$email} siap dipakai.");
    }
}