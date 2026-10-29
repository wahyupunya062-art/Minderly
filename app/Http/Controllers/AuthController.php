<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman pendaftaran.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->arahkanSetelahLogin();
        }

        return view('auth.register');
    }

    /**
     * Memproses pendaftaran akun baru.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:pengguna,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $pengguna = Pengguna::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Login otomatis setelah berhasil mendaftar.
        Auth::login($pengguna);
        $request->session()->regenerate();

        // Pengguna wajib mengisi kuesioner sebelum Dashboard.
        return redirect()->route('kuesioner.create')
            ->with('success', 'Akun berhasil dibuat. Silakan isi kuesioner profil.');
    }

    /**
     * Menampilkan halaman login.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->arahkanSetelahLogin();
        }

        return view('auth.login');
    }

    /**
     * Memproses login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password yang kamu masukkan salah.',
            ]);
        }

        $request->session()->regenerate();

        return $this->arahkanSetelahLogin();
    }
    
public function showForgotPassword()
{
    return view('Auth.forgot-password');
}

public function sendResetLink(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );
    Log::info('Hasil permintaan reset password Minderly', [
    'status' => $status,
    ]);

    if ($status === Password::ResetLinkSent) {
        return back()->with(
            'status',
            'Jika email terdaftar, tautan reset password akan dikirim ke email tersebut.'
        );
    }

    return back()->withErrors([
        'email' => 'Tautan belum dapat dikirim. Periksa email atau coba lagi nanti.',
    ]);
}

public function showResetPassword(Request $request, string $token)
{
    return view('Auth.reset-password', [
        'token' => $token,
        'email' => $request->query('email'),
    ]);
}

public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        ),
        function (Pengguna $user, string $password) {
           $user->forceFill([
                'password' => Hash::make($password),
            ])->setRememberToken(Str::random(60));

            $user->save();
            event(new PasswordReset($user));
        }
    );

    if ($status === Password::PasswordReset) {
        return redirect()->route('login')
            ->with('status', 'Password berhasil diubah. Silakan login.');
    }

    return back()->withErrors([
        'email' => 'Tautan reset tidak valid atau sudah kedaluwarsa. Silakan minta tautan baru.',
    ]);
}


    /**
     * Memeriksa apakah pengguna sudah mengisi kuesioner.
     */
    private function arahkanSetelahLogin()
    {
        $pengguna = Auth::user();

        $sudahMengisi = Schema::hasTable('profil_pengguna')
            && DB::table('profil_pengguna')
                ->where('id_pengguna', $pengguna->id_pengguna)
                ->exists();

        if (!$sudahMengisi) {
            return redirect()->route('kuesioner.create');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Kamu berhasil keluar dari akun.');
    }
}
