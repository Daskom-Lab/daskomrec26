<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Http\Requests\UpdateStageRequest;

class StageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $stages = Stage::with('configurations')->get();
        return inertia('Admin/configuration', ['stages' => $stages]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStageRequest $request, Stage $stage)
    {
        $stage->update($request->only(['success_message', 'fail_message', 'link']));

        $stage->configurations()->update(
            $request->only(['pengumuman_on', 'isi_jadwal_on', 'puzzles_on'])
        );

        return redirect()->back();
    }


}
