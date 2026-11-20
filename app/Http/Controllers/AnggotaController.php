<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AnggotaController extends Controller
{
    public function index(): View
    {
        return view('admin.anggota.index', [
            'anggota' => Anggota::orderBy('nama')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.anggota.create', ['anggota' => new Anggota]);
    }

    public function store(Request $request): RedirectResponse
    {
        Anggota::create($this->validatedData($request));

        return redirect()->route('admin.anggota.index')->with('sukses', 'Anggota berhasil ditambahkan.');
    }

    public function edit(Anggota $anggota): View
    {
        return view('admin.anggota.edit', compact('anggota'));
    }

    public function update(Request $request, Anggota $anggota): RedirectResponse
    {
        $anggota->update($this->validatedData($request, $anggota));

        return redirect()->route('admin.anggota.index')->with('sukses', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota): RedirectResponse
    {
        if ($anggota->user_id) {
            return back()->with('galat', 'Anggota tidak dapat dihapus karena sudah terhubung dengan akun.');
        }

        if ($anggota->peminjaman()->exists()) {
            return back()->with('galat', 'Anggota tidak dapat dihapus karena memiliki riwayat peminjaman.');
        }

        $anggota->delete();

        return redirect()->route('admin.anggota.index')->with('sukses', 'Anggota berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Anggota $anggota = null): array
    {
        $uniqueNis = Rule::unique('anggota', 'nis');

        if ($anggota) {
            $uniqueNis->ignore($anggota->id);
        }

        return $request->validate([
            'nis' => ['required', 'string', 'max:50', $uniqueNis],
            'nama' => ['required', 'string', 'max:255'],
            'kelas' => ['required', 'string', 'max:100'],
            'no_hp' => ['required', 'regex:/^08\d{10}$/'],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah digunakan.',
            'nama.required' => 'Nama anggota wajib diisi.',
            'kelas.required' => 'Kelas wajib diisi.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.regex' => 'Nomor HP harus berformat 08 diikuti 10 angka.',
            'status.required' => 'Status anggota wajib dipilih.',
            'status.in' => 'Status anggota tidak valid.',
        ]);
    }
}
