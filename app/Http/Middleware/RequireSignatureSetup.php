<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireSignatureSetup
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Skip for admin and kadin
        if (in_array($user->role, ['admin', 'kadin'])) {
            return $next($request);
        }
        
        // Skip for signature setup routes
        if ($request->routeIs('signature.*') || $request->routeIs('dashboard') || $request->routeIs('profile.*')) {
            return $next($request);
        }
        
        // Check if user has completed signature setup
        if (!$user->signature_setup_completed || !$user->tanda_tangan) {
            return redirect()->route('signature.setup')
                           ->with('warning', 'Anda harus setup tanda tangan terlebih dahulu sebelum dapat menggunakan sistem.');
        }
        
        return $next($request);
    }
}
