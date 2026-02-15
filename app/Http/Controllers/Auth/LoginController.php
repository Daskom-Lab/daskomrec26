<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
        /**
         * validate request
         */
        $request->validate([
            'nim'     => 'required',
            'password'  => 'required'
        ]);

        //get email and password from request
        $credentials = $request->only('nim', 'password');

        //attempt to login
        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            // Redirect berdasarkan role
            if (Auth::user()->is_admin) {
                return redirect('/admin/home');
            }

            return redirect('/User/home');
        }

        //if login fails
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
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
