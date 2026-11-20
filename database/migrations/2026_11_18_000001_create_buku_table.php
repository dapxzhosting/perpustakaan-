<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori')->restrictOnDelete();
            $table->string('kode_buku', 20)->unique();
            $table->string('judul', 200);
            $table->string('penulis');
            $table->string('penerbit')->nullable();
            $table->unsignedSmallInteger('tahun_terbit');
            $table->unsignedInteger('stok')->default(0);
            $table->string('sampul')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
