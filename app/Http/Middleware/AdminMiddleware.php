<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('AdminMiddleware is running');
        
        if (!auth()->check()) {
            Log::warning('User is not authenticated');
            abort(403, 'Unauthorized action.');
        }
        
        if (!auth()->user()->is_admin) {
            Log::warning('User is not an admin: ' . auth()->user()->email);
            abort(403, 'Unauthorized action.');
        }
        
        Log::info('Admin access granted to: ' . auth()->user()->email);
        return $next($request);
    }
}
