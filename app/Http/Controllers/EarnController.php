<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class EarnController extends Controller
{
    public function index()
    {
        abort_unless(setting_bool('affiliate_enabled', true), 404);

        return view('frontend.earn');
    }

    public function store(Request $request)
    {
        abort_unless(setting_bool('affiliate_enabled', true), 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        // Synthesize an email if none provided (kept unique).
        $email = $data['email'] ?? ('c' . preg_replace('/\D/', '', $data['phone']) . '.' . Str::random(4) . '@contributor.local');

        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $email,
            'password' => Hash::make($data['password']),
            'is_contributor' => true,
            'is_active' => true,
        ]);
        $user->assignRole('contributor');

        Auth::login($user);

        return redirect()->route('dashboard.index')
            ->with('success', 'Welcome aboard! Share your referral link and start earning.');
    }
}
