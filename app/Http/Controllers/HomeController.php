<?php

namespace App\Http\Controllers;

use App\Models\Puzzle;
use App\Models\Stage;
use App\Models\User;
use App\Models\CaasStage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stages = Stage::with('configurations')->get();
        $currentStage = $stages->firstWhere('current_stage', true);

        $totalUsers = User::count();
        $passedUsers = $currentStage
            ? CaasStage::where('stage_id', $currentStage->id)->where('status', 'LOLOS')->count()
            : 0;

        $puzzles = Puzzle::all();

        return inertia('Admin/home', [
            'stages' => $stages,
            'currentStage' => $currentStage,
            'totalUsers' => $totalUsers,
            'passedUsers' => $passedUsers,
            'puzzles' => $puzzles,
        ]);
    }

    public function setCurrentStage(Request $request)
    {
        $request->validate([
            'stage_id' => 'required|exists:stages,id',
        ]);

        // Set all stages to not current
        Stage::query()->update(['current_stage' => false]);

        // Set the selected stage as current
        Stage::where('id', $request->stage_id)->update(['current_stage' => true]);

        return back()->with('success', 'Current phase updated.');
    }

    public function updateConfiguration(Request $request, Stage $stage)
    {
        $request->validate([
            'pengumuman_on' => 'sometimes|boolean',
            'isi_jadwal_on' => 'sometimes|boolean',
        ]);

        $stage->configurations()->update(
            $request->only(['pengumuman_on', 'isi_jadwal_on'])
        );

        return back()->with('success', 'Configuration updated.');
    }

    public function updateStageMessages(Request $request, Stage $stage)
    {
        $request->validate([
            'success_message' => 'nullable|string',
            'fail_message' => 'nullable|string',
            'link' => 'nullable|string',
        ]);

        $stage->update($request->only(['success_message', 'fail_message', 'link']));

        return back()->with('success', 'Messages updated.');
    }
}
