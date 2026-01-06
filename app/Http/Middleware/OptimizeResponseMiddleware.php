<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptimizeResponseMiddleware
{
    /**
     * Handle an incoming request.
     * Optimasi response untuk performa lebih cepat
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Tambahkan headers untuk optimasi browser
        if (method_exists($response, 'header')) {
            // Enable browser caching untuk static assets
            if ($request->is('*.css') || $request->is('*.js') || $request->is('*.jpg') || $request->is('*.png') || $request->is('*.gif')) {
                $response->header('Cache-Control', 'public, max-age=31536000, immutable');
            } else {
                // For PJAX requests, allow short-term caching for faster navigation
                if ($request->header('X-PJAX') || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                    // Allow browser to cache PJAX responses for 30 seconds
                    $response->header('Cache-Control', 'private, max-age=30, must-revalidate');
                    $response->header('Vary', 'Accept, X-PJAX');
                } else {
                    // Cache HTML responses untuk 60 detik
                    $response->header('Cache-Control', 'no-cache, must-revalidate');
                }
            }
            
            // Remove unnecessary headers
            $response->header('X-Powered-By', '');
            
            // Add performance headers
            $response->header('X-Content-Type-Options', 'nosniff');
            $response->header('X-Frame-Options', 'SAMEORIGIN');
        }
        
        return $response;
    }
}

