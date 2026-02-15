<?php

namespace App\Http\Controllers;

use App\Models\Puzzle;
use Illuminate\Http\Request;

class PuzzleController extends Controller
{
    /**
     * Update a puzzle's clue and answer (Admin).
     */
    public function update(Request $request, Puzzle $puzzle)
    {
        $request->validate([
            'clue' => 'nullable|string',
            'answer' => 'required|string',
        ]);

        $puzzle->update($request->only(['clue', 'answer']));

        return back()->with('success', 'Puzzle updated.');
    }

    /**
     * User attempts to unlock a puzzle by submitting an answer.
     * If correct, set status to true (1).
     */
    public function unlock(Request $request, Puzzle $puzzle)
    {
        $request->validate([
            'answer' => 'required|string',
        ]);

        if (strtolower(trim($request->answer)) === strtolower(trim($puzzle->answer))) {
            $puzzle->update(['status' => true]);

            return back()->with('success', 'Correct answer! Core unlocked.');
        }

        return back()->withErrors(['answer' => 'Incorrect answer.']);
    }
}
