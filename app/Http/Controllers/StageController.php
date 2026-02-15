<?php

namespace App\Http\Controllers;

use App\Models\stage;
use App\Http\Requests\StorestageRequest;
use App\Http\Requests\UpdatestageRequest;

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorestageRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(stage $stage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(stage $stage)
    {
        //
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatestageRequest $request, stage $stage)
    {
        $stage->update($request->only(['success_message', 'fail_message', 'link']));

        $stage->configurations()->update(
            $request->only(['pengumuman_on', 'isi_jadwal_on', 'puzzles_on'])
        );

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(stage $stage)
    {
        //
    }
}
