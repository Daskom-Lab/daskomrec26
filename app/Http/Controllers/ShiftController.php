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
    // Mapping nama hari Indonesia ke DAYOFWEEK MySQL (1=Sunday, 2=Monday, ...)
    private const DAY_MAP = [
        'minggu' => 1,
        'senin' => 2,
        'selasa' => 3,
        'rabu' => 4,
        'kamis' => 5,
        'jumat' => 6,
        'sabtu' => 7,
    ];

    public function index()
    {
        $perPage = request('perPage', 5);
        $perPage = in_array($perPage, [5, 10]) ? $perPage : 5;
        $search = request('search', '');

        $shifts = Shift::with(['plottingans.user.profile'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('shift_no', 'like', "%{$search}%")
                      ->orWhere('date', 'like', "%{$search}%");

                    // Cek apakah search cocok dengan nama hari
                    $searchLower = strtolower($search);
                    foreach (self::DAY_MAP as $day => $dayOfWeek) {
                        if (str_contains($day, $searchLower)) {
                            $q->orWhereRaw('DAYOFWEEK(date) = ?', [$dayOfWeek]);
                        }
                    }
                });
            })
            ->orderBy('date', 'asc')
            ->orderBy('time_start', 'asc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'perPage' => $perPage]);
    
        return inertia('Admin/shift', [
            'shifts' => $shifts,
            'filters' => [
                'search' => $search,
            ],
        ]);
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
