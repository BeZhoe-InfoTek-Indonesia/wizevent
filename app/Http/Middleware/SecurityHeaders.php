<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        // Content Security Policy
        $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline' https://fonts.bunny.net; img-src 'self' data: https:; font-src 'self' data: https://fonts.bunny.net; connect-src 'self'; frame-src 'self' https://www.openstreetmap.org https://maps.google.com https://www.google.com";

        // Allow Vite development server in non-production environments
        if (! app()->environment('production')) {
            $csp = "default-src 'self' http://localhost:* http://127.0.0.1:* http://event-management.test:*; script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:* http://127.0.0.1:* http://event-management.test:*; style-src 'self' 'unsafe-inline' https://fonts.bunny.net http://localhost:* http://127.0.0.1:* http://event-management.test:*; img-src 'self' data: https: http://localhost:* http://127.0.0.1:* http://event-management.test:*; font-src 'self' data: https://fonts.bunny.net http://localhost:* http://127.0.0.1:* http://event-management.test:*; connect-src 'self' http://localhost:* http://127.0.0.1:* ws://localhost:* ws://127.0.0.1:* ws://event-management.test:* http://event-management.test:*; frame-src 'self' https://www.openstreetmap.org https://maps.google.com https://www.google.com http://localhost:* http://127.0.0.1:* http://event-management.test:*";
        }

        $response->headers->set('Content-Security-Policy', $csp);

        // Force HTTPS in production
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
