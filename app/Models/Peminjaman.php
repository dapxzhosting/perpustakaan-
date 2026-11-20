<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'anggota_id',
        'buku_id',
        'tgl_pinjam',
        'tgl_jatuh_tempo',
        'tgl_kembali',
        'status',
        'denda',
    ];

    protected function casts(): array
    {
        return [
            'tgl_pinjam' => 'date',
            'tgl_jatuh_tempo' => 'date',
            'tgl_kembali' => 'date',
        ];
    }

    public const DENDA_PER_HARI = 1000;

    public function hariTerlambat(?\Carbon\CarbonInterface $sampai = null): int
    {
        $sampai ??= $this->tgl_kembali ?? today();

        return max(0, (int) $this->tgl_jatuh_tempo->diffInDays($sampai, false));
    }

    public function dendaSaatIni(): int
    {
        if ($this->status === 'dikembalikan') {
            return (int) $this->denda;
        }

        return $this->hariTerlambat(today()) * self::DENDA_PER_HARI;
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }
}
