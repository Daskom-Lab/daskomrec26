<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CaasStage;
use App\Models\Configuration;
use App\Models\Stage;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display the announcement page with user's current stage status and messages.
     */
    public function index()
    {
        $user = Auth::user();

        // Get the current active stage (where current_stage = true)
        $currentStage = Stage::where('current_stage', true)->first();

        if (!$currentStage) {
            return inertia('User/announcement', [
                'userStatus' => 'not_available',
                'successMessage' => '',
                'failMessage' => '',
                'link' => '',
                'shiftEnabled' => false,
                'announcementEnabled' => false,
                'stageName' => '',
            ]);
        }

        // Get the user's CaasStage record for the current stage
        $caasStage = CaasStage::where('user_id', $user->id)
            ->where('stage_id', $currentStage->id)
            ->first();

        // Determine user status based on CaasStage
        $userStatus = 'pending';
        if ($caasStage) {
            $userStatus = $caasStage->status === 'LOLOS' ? 'passed' : 'failed';
        }

        // Get the configuration for this stage
        $configuration = Configuration::where('stage_id', $currentStage->id)->first();

        return inertia('User/announcement', [
            'userStatus' => $userStatus,
            'successMessage' => $currentStage->success_message ?? '',
            'failMessage' => $currentStage->fail_message ?? '',
            'link' => $currentStage->link ?? '',
            'shiftEnabled' => $configuration?->isi_jadwal_on ?? false,
            'announcementEnabled' => $configuration?->pengumuman_on ?? false,
            'stageName' => $currentStage->name ?? '',
        ]);
    }
}
