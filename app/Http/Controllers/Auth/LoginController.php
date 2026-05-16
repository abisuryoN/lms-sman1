<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        // Generate Math Captcha
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        session(['captcha_answer' => $num1 + $num2]);
        $captcha_question = "$num1 + $num2";

        return view('auth.login', compact('captcha_question'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'captcha' => ['required', 'numeric'],
        ], [
            'captcha.required' => 'Hasil perhitungan harus diisi.',
            'captcha.numeric' => 'Hasil perhitungan harus berupa angka.',
        ]);

        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam $seconds detik.",
            ])->onlyInput('email');
        }

        // Validate Captcha
        if ($request->captcha != session('captcha_answer')) {
            RateLimiter::hit($throttleKey, 60);
            return back()->withErrors([
                'captcha' => 'Hasil perhitungan salah. Silakan coba lagi.',
            ])->withInput($request->only('email', 'remember'));
        }

        // Clear captcha session after validation attempt (success or fail)
        session()->forget('captcha_answer');

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if siswa is alumni
            if ($user->role === 'siswa' && $user->siswa && $user->siswa->status === 'alumni') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda sudah berstatus alumni dan tidak dapat mengakses LMS.',
                ])->onlyInput('email');
            }

            return $this->redirectByRole($user);
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'Email atau password tidak sesuai.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole($user)
    {
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'guru' => redirect()->route('guru.dashboard'),
            'siswa' => redirect()->route('siswa.dashboard'),
            default => redirect()->route('login'),
        };
    }
}
