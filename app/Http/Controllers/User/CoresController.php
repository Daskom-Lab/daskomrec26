<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Puzzle;

class CoresController extends Controller
{
    /**
     * Display the cores/puzzle map page with puzzle data from the database.
     */
    public function index()
    {
        $puzzles = Puzzle::all()->map(function ($puzzle) {
            return [
                'id' => $puzzle->id,
                'name' => $puzzle->name,
                'clue' => $puzzle->clue,
                'status' => $puzzle->status, // true = unlocked, false = locked
            ];
        });

        return inertia('User/cores', [
            'puzzles' => $puzzles,
        ]);
    }
}
