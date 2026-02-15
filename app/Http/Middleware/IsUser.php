<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUser
{
    /**
     * Hanya user biasa yang bisa mengakses route ini.
     * Jika admin, redirect ke admin/home.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->is_admin) {
            return redirect('/admin/home');
        }

        return $next($request);
    }
}
