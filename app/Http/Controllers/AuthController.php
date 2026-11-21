<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.peminjaman.index');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $email = Str::lower($credentials['email']);
        // Kunci per email+IP, dan kunci per email saja sebagai cadangan:
        // dengan trustProxies('*') header X-Forwarded-For bisa dipalsukan,
        // sehingga batas berbasis IP saja bisa dihindari.
        $throttleKey = $email.'|'.$request->ip();
        $emailKey = 'login-email|'.$email;

        if (RateLimiter::tooManyAttempts($throttleKey, 5) || RateLimiter::tooManyAttempts($emailKey, 20)) {
            $detik = max(RateLimiter::availableIn($throttleKey), RateLimiter::availableIn($emailKey));

            return back()
                ->withErrors(['email' => "Terlalu banyak percobaan masuk. Coba lagi dalam {$detik} detik."])
                ->onlyInput('email');
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);
            RateLimiter::hit($emailKey, 300);

            return back()
                ->withErrors(['email' => 'Email atau kata sandi salah.'])
                ->onlyInput('email');
        }

        RateLimiter::clear($throttleKey);
        RateLimiter::clear($emailKey);
        $request->session()->regenerate();

        return redirect()->route('admin.peminjaman.index');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('katalog')->with('sukses', 'Anda berhasil keluar.');
    }
}
