<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Get the authenticated user
        $user = auth()->user();
        
        // Additional null check for safety
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // If no specific roles are provided, default to admin only
        if (empty($roles)) {
            $roles = ['admin'];
        }

        // Check if user has any of the required roles
        if (!$this->hasAnyRole($user, $roles)) {
            $roleList = implode(', ', $roles);
            abort(403, "Akses ditolak. Hanya pengguna dengan role ({$roleList}) yang dapat mengakses halaman ini.");
        }

        return $next($request);
    }

    /**
     * Check if user has any of the specified roles
     *
     * @param  mixed  $user
     * @param  array  $roles
     * @return bool
     */
    private function hasAnyRole($user, array $roles): bool
    {
        if (!$user->role) {
            return false;
        }

        $userRole = strtolower(trim($user->role));
        
        foreach ($roles as $role) {
            if ($userRole === strtolower(trim($role))) {
                return true;
            }
        }

        return false;
    }
}