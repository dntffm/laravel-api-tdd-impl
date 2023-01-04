<?php

namespace App\Http\Controllers;

use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Validator;

class CourseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = CourseCategory::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'course_category_name' => 'required'
        ]);

        if($validated->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validated->errors()
            ], 422);
        }

        CourseCategory::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Course category successfully created!'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function show(CourseCategory $courseCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseCategory $courseCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseCategory $courseCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param   int $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($courseCategory)
    {
        $cc = CourseCategory::find($courseCategory);

        if($cc) {
            $cc->delete();

            return response()->json([
                'success' => true,
                'message' => 'Course category successfully deleted!'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Course category not found!'
        ], 404);
    }
}
