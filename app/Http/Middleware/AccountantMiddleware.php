<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AccountantMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('AccountantMiddleware is running');
        
        if (!auth()->check()) {
            Log::warning('User is not authenticated');
            return redirect()->route('login');
        }
        
        if (!auth()->user()->is_accountant && !auth()->user()->is_admin) {
            Log::warning('User is not an accountant or admin: ' . auth()->user()->email);
            return redirect()->route('user.dashboard')
                ->with('error', 'You do not have permission to access this area.');
        }
        
        Log::info('Accountant access granted to: ' . auth()->user()->email);
        return $next($request);
    }
}
