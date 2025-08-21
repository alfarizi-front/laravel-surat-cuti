<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log request untuk debugging
        Log::info('Request Debug', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'route' => $request->route() ? $request->route()->getName() : 'No route',
            'user' => auth()->check() ? [
                'id' => auth()->id(),
                'email' => auth()->user()->email,
                'role' => auth()->user()->role
            ] : 'Guest',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        try {
            $response = $next($request);
            
            // Log successful response
            Log::info('Response Debug', [
                'status' => $response->getStatusCode(),
                'route' => $request->route() ? $request->route()->getName() : 'No route'
            ]);
            
            return $response;
            
        } catch (\Exception $e) {
            // Log error
            Log::error('Request Error', [
                'url' => $request->fullUrl(),
                'route' => $request->route() ? $request->route()->getName() : 'No route',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Re-throw exception
            throw $e;
        }
    }
}
