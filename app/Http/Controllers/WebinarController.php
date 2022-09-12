<?php

namespace App\Http\Controllers;

use App\Models\Webinar;
use Illuminate\Http\Request;
use Validator;

class WebinarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Webinar::orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
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

        ]);

        if($validated->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validated->errors()
            ], 422); 
        }

        Webinar::create($request->all());

        return response()->json([
            'success' => true,
            'message' => "Webinar successfully created!"
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\webinar  $webinar
     * @return \Illuminate\Http\Response
     */
    public function show(webinar $webinar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\webinar  $webinar
     * @return \Illuminate\Http\Response
     */
    public function edit(webinar $webinar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\webinar  $webinar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, webinar $webinar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\webinar  $webinar
     * @return \Illuminate\Http\Response
     */
    public function destroy(webinar $webinar)
    {
        //
    }
}
