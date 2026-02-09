<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stage;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('perPage', 5); // Default to 5 items per page
        $perPage = in_array($perPage, [5, 10]) ? $perPage : 5; // Validate: only allow 5 or 10
        
        $users = User::with('profile', 'caasStage.stage')
            ->join('caas_stages', 'users.id', '=', 'caas_stages.user_id')
            ->orderBy('caas_stages.stage_id', 'desc')
            ->select('users.*')
            ->paginate($perPage);

        $stages = Stage::orderBy('id')->get();
        
        return inertia('Admin/caas', ['users' => $users, 'stages' => $stages]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
        $user = User::create([
            'nim' => $request->nim,
            'password' => bcrypt($request->password),
        ]);

        // Create profile
        $user->profile()->create([
            'name' => $request->name,
            'email' => $request->email,
            'major' => $request->major,
            'class' => $request->class,
            'gender' => $request->gender,
        ]);

        return back()->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
        $user->update([
            'nim' => $request->nim,
            'password' => bcrypt($request->password),
        ]);

        // Update profile
        $user->profile()->update([
            'name' => $request->name,
            'email' => $request->email,
            'major' => $request->major,
            'class' => $request->class,
            'gender' => $request->gender,
        ]);

        return back()->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
        User::destroy($user->id);
        return back()
            ->with('success', 'User deleted successfully.');
    }
}
