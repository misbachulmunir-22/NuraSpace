<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->must_change_password) {
            // Allow profile edit, password update, and logout
            if (
                !$request->routeIs('profile.edit') &&
                !$request->routeIs('password.update') &&
                !$request->routeIs('logout')
            ) {
                return redirect()->route('profile.edit')->with(
                    'warning',
                    'Selamat datang di NuraSpace! Demi privasi dan keamanan, silakan ganti password awal Anda terlebih dahulu.'
                );
            }
        }

        return $next($request);
    }
}
