<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return inertia('login');
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim'      => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('nim', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $targetUrl = Auth::user()->is_admin ? '/admin/home' : '/User/home';

            if ($request->wantsJson()) {
                return response()->json(['redirect_url' => $targetUrl]);
            }

            return redirect($targetUrl);
        }

        // Login Failed
        if ($request->wantsJson()) {
            return response()->json([
                'errors' => ['nim' => ['The provided credentials do not match our records.']]
            ], 422);
        }

        return back()->withErrors([
            'nim' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * destroy
     *
     * @return void
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
