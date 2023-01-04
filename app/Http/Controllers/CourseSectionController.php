<?php

namespace App\Http\Controllers;

use App\Models\CourseSection;
use App\Models\User;
use Illuminate\Http\Request;

class CourseSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($courseId)
    {
        $user = User::findOrfail(auth()->user()->id);
        $data = $user->courses()->where('course_id', $courseId)->first()->sections()->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CourseSection  $courseSection
     * @return \Illuminate\Http\Response
     */
    public function show(CourseSection $courseSection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CourseSection  $courseSection
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseSection $courseSection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseSection  $courseSection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseSection $courseSection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourseSection  $courseSection
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseSection $courseSection)
    {
        //
    }
}
