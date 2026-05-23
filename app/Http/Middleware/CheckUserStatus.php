<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Log::debug('CheckUserStatus middleware called', [
            'path' => $request->getPathInfo(),
            'user' => Auth::user() ? Auth::user()->id : null,
            'is_active' => Auth::user() ? Auth::user()->is_active : null,
        ]);
        
        if (Auth::check() && !Auth::user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your account has been deactivated.'
                ], 403);
            }
            
            return redirect()->route('account.deactivated');
        }

        return $next($request);
    }
}
