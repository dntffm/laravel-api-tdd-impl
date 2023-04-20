<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\CourseSubSection;
use App\Models\Quiz;
use App\Models\QuizAnswer;
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
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $data = Course::with(['category', 'image', 'sections'])
        ->where('id', $id)
        ->first();

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
            $course = Course::create($request->except(['course_sections', 'thumbnail']));
            
            if($request->has('course_sections')) {
                foreach($request->course_sections as $sectione) {
                    
                    $section = $course->sections()->create([
                        'section_name' => $sectione['section_name'],
                        'course_id' => $course->id
                    ]);

                    if($sectione['sub_sections']) {
                        $subsections = $sectione['sub_sections'];
                        foreach($subsections as $subsection) {
                            if($subsection['type'] === 'quiz') {
                                $ss = CourseSubSection::create([
                                    'title' => $subsection['title'],
                                    'type' => 'quiz',
                                    'section_id' => $section->id
                                ]);
                                
                                foreach($subsection['questions'] as $question) {
                                    $quiz = Quiz::create([
                                        'question' => $question['question'],
                                        'sub_section_id' => $ss->id
                                    ]);

                                    foreach($question['answers'] as $answer) {
                                        QuizAnswer::create([
                                            'answer' => $answer['answer'],
                                            'is_correct' => $answer['is_correct'],
                                            'quiz_id' => $quiz->id
                                        ]);
                                    }
                                }
    
                            } else if($subsection['type'] === 'video') {
                                CourseSubSection::create([
                                    'title' => $subsection['title'],
                                    'video_url' => $subsection['video_url'],
                                    'type' => 'video',
                                    'section_id' => $section->id
                                ]);
                            } else {
                                continue;
                            }
                        }

                    }
                }

            }

            if($request->has('thumbnail')) {
                $thumbnail = $request
                ->file('thumbnail')
                ->storeAs('public/course', time().$request->thumbnail->getClientOriginalName());
                
                $image = $course->image()->create([
                    'url' => $thumbnail,
                    'type' => 'image'
                ]);

                $course->update([
                    'thumbnail' => $image->id
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Course successfully created!'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

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
     * @param  int  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $course)
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
        
        $courseChosen = Course::findOrFail($course);

        $courseChosen->update($request->except(['course_sections', 'thumbnail']));

        if($request->has('course_sections')) {
            foreach($request->course_sections as $section){
                $courseChosen->sections()->where('id', $section['id'])->first()->update($section);
            }
        }

        if($request->has('thumbnail')) {
            $thumbnail = $request
            ->file('thumbnail')
            ->storeAs('public/course', time().$request->thumbnail->getClientOriginalName());
            
            $courseChosen->image()->update([
                'url' => $thumbnail,
                'type' => 'image'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Course Successfully Updated!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
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
