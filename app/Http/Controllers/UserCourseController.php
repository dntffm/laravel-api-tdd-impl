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

        return $this->sendResponse('Fetched successfully!', $data);
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
            return $this->sendError('Course Not Found!', 404);
        }

        if($course->price < 1) {
            UserCourse::create([...$input, 'status' => 'active']);

            return $this->sendResponse('You successfully join this course!', 200);
        }

        /* $checkoutService = new CheckoutService();
        dd($checkoutService->createInvoice()); */

        UserCourse::create([...$input, 'status' => 'active']);

        return $this->sendResponse('You successfully join this course!', 200);
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
            return $this->sendError('Course already taken!', 404);
        }

        $course = Course::with(['sections.subsections'])->find($course);

        return $this->sendResponse('Fetched successfully!', [
                'course' => $course,
                'sections' => $course->sections,
        ]);
    }
}
