<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\UserCourse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Course::orderBy('created_at', 'desc')
        ->with(['category', 'image'])
        ->get();

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
            'course_name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'discount' => 'required',
            'thumbnail' => 'required',
            'course_category' => 'required',
            'course_sections' => 'array'
        ]);

        if($validated->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validated->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $course = Course::create($request->except('course_sections'));
            
            if($request->has('course_sections')) {
                foreach($request->course_sections as $section) {
                    $course->sections()->create($section);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return response()->json([
            'success' => true,
            'message' => 'Course successfully created!'
        ], 200);
    }

    /**
     * Display the specified resource by user login.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function mycourses()
    {
        $data = Course::orderBy('created_at', 'desc')
                ->whereHas('user')
                ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $course)
    {
        $courseChosen = Course::findOrFail($course);

        $courseChosen->update($request->all());

        if($request->has('course_sections')) {
            foreach($request->course_sections as $section){
                $course->sections()->where('id', $section->id)->first()->update($section);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Course Successfully Updated!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy($course)
    {
        $courseChosen = Course::find($course);

        if(!$courseChosen) {
            return response()->json([
                'success' => false,
                'message' => 'Course Not Found!'
            ], 404);
        }

        $courseChosen->delete($course);
        return response()->json([
            'success' => true,
            'message' => 'Course Successfully Deleted!'
        ], 200);
    }
}
