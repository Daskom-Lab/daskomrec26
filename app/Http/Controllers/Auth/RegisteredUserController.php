<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Stage;
use App\Models\CaasStage;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /*
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nim' => 'required|string|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'nim' => $request->nim,
            'password' => Hash::make($request->password),
        ]);

        // Create CaasStage with Administration stage (default)
        $administrationStage = Stage::where('name', 'Administration')->first();
        if ($administrationStage) {
            CaasStage::create([
                'user_id' => $user->id,
                'stage_id' => $administrationStage->id,
                'status' => 'GAGAL', // Default status
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect('/User/home');
    }
}
