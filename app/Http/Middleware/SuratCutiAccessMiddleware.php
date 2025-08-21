<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SuratCuti;

class SuratCutiAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $suratCutiId = $request->route('surat_cuti') ?? $request->route('id');

        if (!$suratCutiId) {
            return $next($request);
        }

        $suratCuti = SuratCuti::find($suratCutiId);

        if (!$suratCuti) {
            abort(404, 'Surat cuti tidak ditemukan');
        }

        // Admin dapat mengakses semua surat
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Pengaju dapat mengakses surat cuti miliknya
        if ($suratCuti->pengaju_id === $user->id) {
            return $next($request);
        }

        // User lain hanya dapat mengakses jika ada dalam alur disposisi
        $hasDisposisi = $suratCuti->disposisiCuti()
                                  ->where('user_id', $user->id)
                                  ->exists();

        if (!$hasDisposisi) {
            abort(403, 'Anda tidak memiliki akses ke surat cuti ini');
        }

        return $next($request);
    }
}
