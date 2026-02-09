<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\UpdateShiftRequest;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('perPage', 5); // Default to 5 items per page
        $perPage = in_array($perPage, [5, 10]) ? $perPage : 5; // Validate: only allow 5 or 10
        
        $shifts = Shift::with(['plottingans.user.profile'])
            ->orderBy('date', 'asc')
            ->orderBy('time_start', 'asc')
            ->paginate($perPage);
    
        return inertia('Admin/shift', ['shifts' => $shifts]);
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
    public function store(StoreShiftRequest $request)
    {
        Shift::create([
            'shift_no' => $request->shift,
            'date' => $request->date,
            'time_start' => $request->timeStart,
            'time_end' => $request->timeEnd,
            'kuota' => $request->quota,
        ]);

        return back()
            ->with('success', 'Shift created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shift $shift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shift $shift)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShiftRequest $request, Shift $shift)
    {
        Shift::where('id', $shift->id)->update([
            'shift_no' => $request->shift,
            'date' => $request->date,
            'time_start' => $request->timeStart,
            'time_end' => $request->timeEnd,
            'kuota' => $request->quota,
        ]);

        return back()
            ->with('success', 'Shift updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shift $shift)
    {
        //
        Shift::destroy($shift->id);
        return back()
            ->with('success', 'Shift deleted successfully.');
    }
}
