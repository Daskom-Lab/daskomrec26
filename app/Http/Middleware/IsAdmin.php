<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Hanya admin yang bisa mengakses route ini.
     * Jika bukan admin, redirect ke User/home.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->is_admin) {
            return redirect('/User/home');
        }

        return $next($request);
    }
}
