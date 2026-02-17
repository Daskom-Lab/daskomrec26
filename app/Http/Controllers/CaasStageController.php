<?php

namespace App\Http\Controllers;

use App\Models\CaasStage;
use Illuminate\Http\Request;

class CaasStageController extends Controller
{
    /**
     * Update the stage for a caas stage record.
     */
    public function updateStage(Request $request, CaasStage $caasstage)
    {
        $request->validate([
            'stage_id' => 'required|exists:stages,id',
        ]);

        $caasstage->update([
            'stage_id' => $request->stage_id,
        ]);

        return back()->with('success', 'Stage updated successfully.');
    }

    /**
     * Update the status for a caas stage record.
     */
    public function updateStatus(Request $request, CaasStage $caasstage)
    {
        $request->validate([
            'status' => 'required|in:LOLOS,GAGAL,PROSES',
        ]);

        $caasstage->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status updated successfully.');
    }
}
