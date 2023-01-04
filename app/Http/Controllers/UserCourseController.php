<?php

namespace App\Http\Controllers;

use App\Http\Services\CheckoutService;
use App\Models\Course;
use App\Models\User;
use App\Models\UserCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::findOrfail(auth()->user()->id);
        $data = $user->courses()->with('image')->get();

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
        $input['user_id'] = Auth::user()->id;
        $input['course_id'] = $request->course_id;

        $course = Course::find($request->course_id);
       
        if(!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course Not Found!'
            ], 404);
        }
        
        if($course->price < 1) {
            UserCourse::create([...$input, 'status' => 'active']);
            
            return response()->json([
                'success' => true,
                'message' => 'You successfully join this course!'
            ], 200);
        }

        UserCourse::create([...$input, 'status' => 'inactive']);
            
        return response()->json([
            'success' => true,
            'message' => 'You successfully join this course!'
        ], 200);
        /* $checkoutService = new CheckoutService();
        dd($checkoutService->createInvoice()); */

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $course
     * @return \Illuminate\Http\Response
     */
    public function show($course)
    {
        $courseIsTaken = UserCourse::where('user_id', auth()->user()->id)
        ->where('course_id', $course)
        ->where('status', 'active')
        ->first();

        if(!$courseIsTaken)  {
            return response()->json([

            ], 404);
        }

        $course = Course::with(['sections.subsections'])->find($course);

        return response()->json([
            'data' => [
                'course' => $course,
                'sections' => $course->sections,
            ]
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserCourse  $userCourse
     * @return \Illuminate\Http\Response
     */
    public function edit(UserCourse $userCourse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserCourse  $userCourse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserCourse $userCourse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserCourse  $userCourse
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserCourse $userCourse)
    {
        //
    }
}
