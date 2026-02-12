<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->hasRole(['Super Admin', 'Event Manager', 'Finance Admin', 'Check-in Staff'])) {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        // Visitors go to events page
        if ($user && $user->hasRole('Visitor')) {
            return redirect()->route('events.index');
        }

        return $next($request);
    }
}
