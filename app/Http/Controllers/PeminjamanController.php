<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['dipinjam', 'dikembalikan', 'terlambat'])],
            'q' => ['nullable', 'string', 'max:100'],
        ], [
            'status.in' => 'Status peminjaman tidak valid.',
            'q.max' => 'Pencarian maksimal 100 karakter.',
        ]);
        $keyword = trim($filters['q'] ?? '');
        $status = $filters['status'] ?? '';

        $peminjaman = Peminjaman::with(['anggota', 'buku'])
            ->when($status === 'terlambat', fn (Builder $query) => $query
                ->where('status', 'dipinjam')
                ->whereDate('tgl_jatuh_tempo', '<', today()))
            ->when($status !== '' && $status !== 'terlambat', fn (Builder $query) => $query->where('status', $status))
            ->when($keyword !== '', fn (Builder $query) => $query->whereHas(
                'anggota',
                fn (Builder $anggota) => $anggota->where('nama', 'like', "%{$keyword}%")
            ))
            ->orderByDesc('tgl_pinjam')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.peminjaman.index', [
            'peminjaman' => $peminjaman,
            'keyword' => $keyword,
            'statusTerpilih' => $status,
        ]);
    }

    public function create(): View
    {
        return view('admin.peminjaman.create', [
            'anggota' => Anggota::where('status', 'aktif')->orderBy('nama')->get(),
            'buku' => Buku::with('kategori')->orderBy('judul')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'anggota_id' => ['required', 'integer', 'exists:anggota,id'],
            'buku_id' => ['required', 'integer', 'exists:buku,id'],
        ], [
            'anggota_id.required' => 'Anggota wajib dipilih.',
            'anggota_id.exists' => 'Anggota yang dipilih tidak tersedia.',
            'buku_id.required' => 'Buku wajib dipilih.',
            'buku_id.exists' => 'Buku yang dipilih tidak tersedia.',
        ]);

        DB::transaction(function () use ($data): void {
            $anggota = Anggota::whereKey($data['anggota_id'])->lockForUpdate()->firstOrFail();
            $buku = Buku::whereKey($data['buku_id'])->lockForUpdate()->firstOrFail();

            $this->catatPeminjaman($anggota, $buku);
        });

        return redirect()->route('admin.peminjaman.index')->with('sukses', 'Peminjaman berhasil dicatat.');
    }

    public function kembalikan(Peminjaman $peminjaman): RedirectResponse
    {
        DB::transaction(function () use ($peminjaman): void {
            $pinjaman = Peminjaman::whereKey($peminjaman->id)->lockForUpdate()->firstOrFail();

            if ($pinjaman->status !== 'dipinjam') {
                throw ValidationException::withMessages([
                    'peminjaman' => 'Peminjaman ini sudah dikembalikan.',
                ]);
            }

            $buku = Buku::whereKey($pinjaman->buku_id)->lockForUpdate()->firstOrFail();
            $hariTerlambat = $pinjaman->hariTerlambat(today());

            $pinjaman->update([
                'tgl_kembali' => today(),
                'status' => 'dikembalikan',
                'denda' => $hariTerlambat * Peminjaman::DENDA_PER_HARI,
            ]);
            $buku->increment('stok');
        });

        return redirect()->route('admin.peminjaman.index')->with('sukses', 'Buku berhasil dikembalikan dan denda telah dihitung.');
    }

    private function catatPeminjaman(Anggota $anggota, Buku $buku): void
    {
        $anggotaField = 'anggota_id';
        $bukuField = 'buku_id';

        if ($anggota->status !== 'aktif') {
            throw ValidationException::withMessages([
                $anggotaField => 'Anggota nonaktif tidak dapat meminjam buku.',
            ]);
        }

        if ($buku->stok < 1) {
            throw ValidationException::withMessages([
                $bukuField => 'Stok buku habis dan tidak dapat dipinjam.',
            ]);
        }

        $pinjamanAktif = Peminjaman::where('anggota_id', $anggota->id)
            ->where('status', 'dipinjam');

        if ($pinjamanAktif->count() >= 3) {
            throw ValidationException::withMessages([
                $anggotaField => 'Anggota sudah mencapai batas maksimal 3 pinjaman aktif.',
            ]);
        }

        if ((clone $pinjamanAktif)->where('buku_id', $buku->id)->exists()) {
            throw ValidationException::withMessages([
                $bukuField => 'Anggota masih meminjam judul buku ini.',
            ]);
        }

        $tanggalPinjam = today();

        Peminjaman::create([
            'anggota_id' => $anggota->id,
            'buku_id' => $buku->id,
            'tgl_pinjam' => $tanggalPinjam,
            'tgl_jatuh_tempo' => $tanggalPinjam->copy()->addDays(7),
            'status' => 'dipinjam',
        ]);

        $buku->decrement('stok');
    }
}
