<?php

namespace App\Http\Controllers;

use App\Models\Plottingan;
use App\Models\Shift;
use App\Http\Requests\StoreplottinganRequest;
use App\Http\Requests\UpdateplottinganRequest;

class PlottinganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('perPage', 5);
        $perPage = in_array($perPage, [5, 10]) ? $perPage : 5;
        
        // Get shifts with their plottingans count
        $shifts = Shift::withCount('plottingans')
            ->paginate($perPage);
    
        return inertia('Admin/plottingan', ['shifts' => $shifts]);
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
    public function store(StoreplottinganRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(plottingan $plottingan)
    {
        //
    }

    /**
     * Get all users assigned to a specific shift.
     */
    public function shiftUsers($shiftId)
    {
        $plottingans = Plottingan::with(['user.profile'])
            ->where('shift_id', $shiftId)
            ->get();

        return response()->json($plottingans);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(plottingan $plottingan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateplottinganRequest $request, plottingan $plottingan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(plottingan $plottingan)
    {
        //
    }
}
