<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menyamakan skema dengan jobsheet (users hanya untuk petugas, anggota tanpa akun).
 * Pada database baru migration ini tidak melakukan apa-apa. Pada database yang
 * pernah memakai kolom role / user_id, akun non-petugas dihapus lebih dulu agar
 * tidak ada akun anggota yang tiba-tiba bisa membuka panel petugas.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            DB::table('users')->where('role', '!=', 'petugas')->delete();
        }

        if (Schema::hasColumn('anggota', 'user_id')) {
            Schema::table('anggota', function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }

        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }

    public function down(): void
    {
        // Tidak dapat dikembalikan: fitur akun anggota sudah dilepas.
    }
};
