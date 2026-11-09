<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('admin.login');
        }

        if (! $user->can('access_admin')) {
            abort(403, 'You do not have access to the admin panel.');
        }

        if (! $user->is_active) {
            auth()->logout();
            return redirect()->route('admin.login')->withErrors(['email' => 'Your account is disabled.']);
        }

        return $next($request);
    }
}
