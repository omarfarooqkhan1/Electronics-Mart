<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // For web routes (admin panel)
        if ($request->expectsJson() || $request->is('api/*')) {
            // API request
            if (!$request->user()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            if ($request->user()->role !== 'admin') {
                return response()->json(['message' => 'Forbidden. Admin access required.'], 403);
            }
        } else {
            // Web request
            if (!Auth::check()) {
                return redirect()->route('admin.login');
            }

            if (Auth::user()->role !== 'admin') {
                Auth::logout();
                return redirect()->route('admin.login')->withErrors([
                    'email' => 'Access denied. Admin privileges required.'
                ]);
            }
        }

        return $next($request);
    }
}