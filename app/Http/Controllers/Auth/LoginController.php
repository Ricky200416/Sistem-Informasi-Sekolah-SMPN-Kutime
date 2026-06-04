<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login.
     * Jika sudah login, redirect ke dashboard sesuai role.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Proses login.
     * Mendukung password bcrypt dan plain text (otomatis upgrade ke bcrypt).
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Email tidak ditemukan
        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'Email tidak ditemukan.',
            ]);
        }

        $password = $request->password;

        // Deteksi apakah password sudah bcrypt
        $isBcrypt = str_starts_with($user->password, '$2y$')
                 || str_starts_with($user->password, '$2a$');

        if ($isBcrypt) {
            if (! Hash::check($password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => 'Password salah.',
                ]);
            }
        } else {
            // Password masih plain text
            if ($password !== $user->password) {
                throw ValidationException::withMessages([
                    'email' => 'Password salah.',
                ]);
            }
            // Auto-upgrade ke bcrypt
            $user->update(['password' => Hash::make($password)]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    /**
     * Logout — hapus sesi dan kembali ke halaman login.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirect ke dashboard sesuai role.
     * Gunakan intended() agar jika user mencoba akses URL tertentu
     * sebelum login, akan diarahkan ke URL tersebut setelah berhasil login.
     */
    private function redirectByRole(User $user): RedirectResponse
    {
        $route = match ($user->role) {
            'admin' => 'admin.dashboard',
            'guru'  => 'guru.dashboard',
            'siswa' => 'siswa.dashboard',
            default => null,
        };

        if (! $route) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Role tidak dikenali. Hubungi administrator.',
            ]);
        }

        return redirect()->intended(route($route));
    }
}