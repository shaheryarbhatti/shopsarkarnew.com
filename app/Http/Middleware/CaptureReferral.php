<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CaptureReferral
{
    /** How long the referral stays saved on the customer's device (1 year). */
    protected int $cookieMinutes = 525600;

    /**
     * Store a valid ?ref= code so we can attribute a contributor commission.
     * It is kept in the session for the current visit AND in a long-lived
     * cookie on the device, so the attribution survives even if the customer
     * returns later without the referral link.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->filled('ref')) {
            $code = trim($request->query('ref'));
            if ($code && User::where('referral_code', $code)->where('is_contributor', true)->exists()) {
                session(['ref_code' => $code]);
                Cookie::queue('ref_code', $code, $this->cookieMinutes);
            }
        }

        return $next($request);
    }
}
