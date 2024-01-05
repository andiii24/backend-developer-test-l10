<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //  lets assume that there is a badge creatting page from the front end where badges are created, this way it is scalable
     public function index()
    {

        //here will be code to redirect to a page where we list badges those are created
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //here will be code to redirect to a page where we create badges
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'numberOfAchivment' => 'required',
        ]);
        // dd($request);
        $badge = new Badge;
        $badge->name = $request->name;
        $badge->numberOfAchivment = $request->numberOfAchivment;
        $badge->save();
        // lets assume badge-store is a route that takes as to the index where we view badges
        return redirect()->route('badge-index')
            ->with('success', 'Badge added successfully.');    }

    /**
     * Display the specified resource.
     */
    public function show(Badge $badge)
    {
        //we can view a specific badge here
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Badge $badge)
    {
        //we can go to edit badge page
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Badge $badge)
    {
        //we can edit and update here
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Badge $badge)
    {
        //we can destroy the badge over here
    }
}
