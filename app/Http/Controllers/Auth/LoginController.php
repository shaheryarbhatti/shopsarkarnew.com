<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /** Allowed failed attempts before a temporary lockout, and the lockout window. */
    private const MAX_ATTEMPTS = 5;
    private const DECAY_SECONDS = 60;

    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $login = trim($data['login']);

        // Brute-force protection: block after too many failed attempts (per login + IP).
        $throttleKey = 'login:' . Str::lower($login) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'login' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->onlyInput('login');
        }

        // Allow signing in with either an email address or a phone number.
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $email = $login;
        } else {
            // Phone: match tolerantly, ignoring dashes, spaces and a leading +.
            $digits = preg_replace('/\D/', '', $login);
            $user = User::whereRaw("REPLACE(REPLACE(REPLACE(phone,'-',''),' ',''),'+','') = ?", [$digits])->first();
            $email = $user?->email;
        }

        $credentials = ['email' => $email, 'password' => $data['password']];

        if ($email && Auth::attempt($credentials, $request->boolean('remember'))) {
            if (! Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors(['login' => 'Your account is disabled.'])->onlyInput('login');
            }
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            // Admins go to the admin panel, everyone else to the dashboard.
            if (Auth::user()->can('access_admin') && \Illuminate\Support\Facades\Route::has('admin.dashboard')) {
                return redirect()->intended(route('admin.dashboard'));
            }
            return redirect()->intended(route('dashboard.index'));
        }

        RateLimiter::hit($throttleKey, self::DECAY_SECONDS);

        return back()->withErrors(['login' => 'These credentials do not match our records.'])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
