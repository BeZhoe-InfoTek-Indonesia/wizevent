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
        $csp = "default-src 'self' https://portaldev.dwi-coding-aja.web.id https://bz-management.test; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://portaldev.dwi-coding-aja.web.id https://bz-management.test https://static.cloudflareinsights.com; style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://portaldev.dwi-coding-aja.web.id https://bz-management.test; img-src 'self' data: https: https://portaldev.dwi-coding-aja.web.id https://bz-management.test; font-src 'self' data: https://fonts.bunny.net https://portaldev.dwi-coding-aja.web.id https://bz-management.test; connect-src 'self' https://portaldev.dwi-coding-aja.web.id https://bz-management.test; frame-src 'self' https://www.openstreetmap.org https://maps.google.com https://www.google.com https://portaldev.dwi-coding-aja.web.id https://bz-management.test";

        // Allow Vite development server and bz-management.test (http/https, any port) in non-production environments
        if (! app()->environment('production')) {
            $csp = "default-src 'self' http://localhost:* http://127.0.0.1:* https://portaldev.dwi-coding-aja.web.id http://bz-management.test:* https://bz-management.test:*; script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:* http://127.0.0.1:* https://portaldev.dwi-coding-aja.web.id http://bz-management.test:* https://bz-management.test:* https://static.cloudflareinsights.com; style-src 'self' 'unsafe-inline' https://fonts.bunny.net http://localhost:* http://127.0.0.1:* https://portaldev.dwi-coding-aja.web.id http://bz-management.test:* https://bz-management.test:*; img-src 'self' data: https: http://localhost:* http://127.0.0.1:* https://portaldev.dwi-coding-aja.web.id http://bz-management.test:* https://bz-management.test:*; font-src 'self' data: https://fonts.bunny.net http://localhost:* http://127.0.0.1:* https://portaldev.dwi-coding-aja.web.id http://bz-management.test:* https://bz-management.test:*; connect-src 'self' http://localhost:* http://127.0.0.1:* ws://localhost:* ws://127.0.0.1:* wss://bz-management.test:* https://portaldev.dwi-coding-aja.web.id http://bz-management.test:* https://bz-management.test:*; frame-src 'self' https://www.openstreetmap.org https://maps.google.com https://www.google.com http://localhost:* http://127.0.0.1:* https://portaldev.dwi-coding-aja.web.id http://bz-management.test:* https://bz-management.test:*";
        }

        $response->headers->set('Content-Security-Policy', $csp);

        // Force HTTPS in production
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
