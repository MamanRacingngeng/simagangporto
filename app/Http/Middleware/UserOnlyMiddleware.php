<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserOnlyMiddleware
{
    /**
     * Handle an incoming request.
     * Memastikan hanya user biasa (bukan admin) yang bisa mengakses route
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->isAdmin()) {
            // Admin yang mencoba akses route user akan di-redirect ke dashboard admin
            return redirect()->route('admin.dashboard')
                ->with('error', 'Akses ditolak. Admin tidak dapat mengakses halaman user.');
        }

        return $next($request);
    }
}
