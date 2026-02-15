<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Plottingan;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    /**
     * Display shifts and whether the user has already picked one.
     */
    public function index()
    {
        $user = Auth::user();

        $shifts = Shift::withCount('plottingans')
            ->orderBy('date', 'asc')
            ->orderBy('time_start', 'asc')
            ->get();

        // Check if user already picked a shift
        $chosenPlottingan = Plottingan::with('shift')
            ->where('user_id', $user->id)
            ->first();

        return inertia('User/shift', [
            'shifts' => $shifts,
            'hasChosen' => $chosenPlottingan !== null,
            'chosenShift' => $chosenPlottingan?->shift,
        ]);
    }

    /**
     * Pick a shift (create a plottingan record).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if user already picked a shift
        $existing = Plottingan::where('user_id', $user->id)->exists();
        if ($existing) {
            return back()->withErrors(['shift' => 'You have already picked a shift.']);
        }

        $request->validate([
            'shift_id' => 'required|exists:shifts,id',
        ]);

        $shift = Shift::withCount('plottingans')->findOrFail($request->shift_id);

        // Check if shift is full
        if ($shift->plottingans_count >= $shift->kuota) {
            return back()->withErrors(['shift' => 'This shift is already full.']);
        }

        Plottingan::create([
            'user_id' => $user->id,
            'shift_id' => $request->shift_id,
        ]);

        return back()->with('success', 'Shift picked successfully!');
    }
}
