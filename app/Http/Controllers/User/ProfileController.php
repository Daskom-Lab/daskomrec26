<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Profile;


class ProfileController extends Controller
{
    public function index(){
        $user = Auth::user();

        //buat ambil data profile
        $profile= $user->profile ?? new Profile();

        return Inertia ::render('User/profile',[
            'auth' => [
                'user' => $user, // kirim data user
                'profile' => $profile // kirim data profile
            ]
        ]);
    }

    public function update(Request $request){
        $user = Auth::user();

        //validasi input
        $validated = $request->validate([
            'nim' => 'required|string|max:20|unique:users,nim,'. $user->id,
            'name' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
        ]);

        $user->update([
            'nim' => $request->nim,
        ]);

        $user->profile()-> updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' =>$request->name,
                'major' =>$request->major,
                'class' =>$request->class,
                'gender' =>$request->gender,
            ]
            
        );

        return back()->with('success', 'Profile updated successfully.');
    }
}
