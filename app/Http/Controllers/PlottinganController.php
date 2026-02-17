<?php

namespace App\Http\Controllers;

use App\Models\Plottingan;
use App\Models\Shift;
use App\Exports\PlottinganExport;
use Maatwebsite\Excel\Facades\Excel;

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
     * Export all plottingan data to Excel
     */
    public function export()
    {
        return Excel::download(new PlottinganExport, 'Plottingan_Export.xlsx');
    }
}
