<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->role === 'petugas'
                ? redirect()->route('admin.peminjaman.index')
                : redirect()->route('katalog');
        }

        return view('auth.login', [
            'bukuId' => request()->query('buku_id', old('buku_id')),
        ]);
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->role === 'petugas'
                ? redirect()->route('admin.peminjaman.index')
                : redirect()->route('katalog');
        }

        return view('auth.register', [
            'bukuId' => request()->query('buku_id', old('buku_id')),
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'buku_id' => ['nullable', 'integer', 'exists:buku,id'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'buku_id.exists' => 'Buku yang dipilih tidak tersedia.',
        ]);
        $bukuId = $credentials['buku_id'] ?? null;
        unset($credentials['buku_id']);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau kata sandi salah.'])
                ->onlyInput('email', 'buku_id');
        }

        $request->session()->regenerate();

        if (Auth::user()->role === 'petugas') {
            return redirect()->route('admin.peminjaman.index');
        }

        return $bukuId
            ? redirect()->route('buku.show', $bukuId)
            : redirect()->route('katalog');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:50'],
            'kelas' => ['required', 'string', 'max:100'],
            'no_hp' => ['required', 'regex:/^08\d{10}$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'buku_id' => ['nullable', 'integer', 'exists:buku,id'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nis.required' => 'NIS wajib diisi.',
            'kelas.required' => 'Kelas wajib diisi.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.regex' => 'Nomor HP harus berformat 08 diikuti 10 angka.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sama.',
            'buku_id.exists' => 'Buku yang dipilih tidak tersedia.',
        ]);
        $bukuId = $data['buku_id'] ?? null;
        unset($data['buku_id']);

        $user = DB::transaction(function () use ($data) {
            $anggota = Anggota::where('nis', $data['nis'])->lockForUpdate()->first();

            if ($anggota) {
                $identitasSesuai = mb_strtolower(trim($anggota->nama)) === mb_strtolower(trim($data['nama']))
                    && mb_strtolower(trim($anggota->kelas)) === mb_strtolower(trim($data['kelas']))
                    && $anggota->no_hp === $data['no_hp'];

                if ($anggota->user_id) {
                    throw ValidationException::withMessages([
                        'nis' => 'NIS ini sudah terhubung dengan akun.',
                    ]);
                }

                if (! $identitasSesuai) {
                    throw ValidationException::withMessages([
                        'nis' => 'Data anggota tidak cocok. Periksa kembali NIS, nama, kelas, dan nomor HP.',
                    ]);
                }

                if ($anggota->status !== 'aktif') {
                    throw ValidationException::withMessages([
                        'nis' => 'Status anggota tidak aktif. Hubungi petugas perpustakaan.',
                    ]);
                }
            } else {
                $anggota = new Anggota([
                    'nis' => $data['nis'],
                    'nama' => $data['nama'],
                    'kelas' => $data['kelas'],
                    'no_hp' => $data['no_hp'],
                    'status' => 'aktif',
                ]);
            }

            $user = static::createMemberUser($data);
            $anggota->user()->associate($user);
            $anggota->save();

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        $redirect = $bukuId
            ? redirect()->route('buku.show', $bukuId)
            : redirect()->route('katalog');

        return $redirect->with('sukses', 'Akun berhasil dibuat. Silakan pilih buku yang ingin dipinjam.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('katalog')->with('sukses', 'Anda berhasil keluar.');
    }

    private static function createMemberUser(array $data): \App\Models\User
    {
        $user = \App\Models\User::create([
            'name' => $data['nama'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
        $user->role = 'anggota';
        $user->save();

        return $user;
    }
}
