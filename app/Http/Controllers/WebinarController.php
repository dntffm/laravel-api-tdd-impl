<?php

namespace App\Http\Controllers;

use App\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $data = Webinar::with('thumbnail')
        ->orderBy('created_at', 'desc')
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
            "webinar_name" => "required",
            "description" => "required",
            "price" => "required",
            "link" => "required",
            "started_at" => "required",
            "register_end_at" => "required",
        ]);

        if($validated->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validated->errors()
            ], 422); 
        }
        
        DB::beginTransaction();
        try {
            $webinar = Webinar::create($request->except('thumbnail'));
        
            if($request->has('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailName = $thumbnail->storeAs('public/webinar', time() . $thumbnail->getClientOriginalName());
                $wt = $webinar->thumbnail()->create([
                    'url' => $thumbnailName,
                    'type' => 'image'
                ]);
        
                $webinar->update([
                    'thumbnail' => $wt->id
                ]);
            }
            
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Webinar successfully created!"
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
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
     * @param  int  $webinar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $webinar)
    {
        $webinar = Webinar::find($webinar);

        if(!$webinar) {
            return response()->json([
                'success' => false,
                'message' => 'Webinar Not Found!'
            ], 404);
        }

        $webinar->update($request->all());

        if($request->has('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = $thumbnail->storeAs('public/webinar', time() . $thumbnail->getClientOriginalName());
            $wt = $webinar->thumbnail()->create([
                'url' => $thumbnailName,
                'type' => 'image'
            ]);

            $webinar->update([
                'thumbnail' => $wt->id
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Webinar successfully updated!"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $webinar
     * @return \Illuminate\Http\Response
     */
    public function destroy($webinar)
    {
        $webinarChosen = Webinar::find($webinar);

        if(!$webinarChosen) {
            return response()->json([
                'success' => false,
                'message' => 'Webinar Not Found!'
            ], 404);
        }
        
        $webinarChosen->destroy($webinar);
        return response()->json([
            'success' => true,
            'message' => "Webinar successfully deleted!"
        ], 200);
    }

    public function joinWebinar(Request $request)
    {
        $user = Auth::user();
        $webinar = $request->webinar_id;
        $webinarChosen = Webinar::where('id', $webinar)->first();
        
        if(!$webinarChosen) {
            return response()->json([
                'success' => false,
                'message' => 'Webinar Not Found!'
            ], 404);
        }


        if($webinarChosen->price < 1) {
            $user->agendas()->attach($webinar);

            return response()->json([
                'success' => true,
                'message' => "Webinar successfully joined!"
            ], 200);
        }
        //PAYMENT GATEWAY SERVICE
        $user->agendas()->attach($webinar);

        return response()->json([
            'success' => true,
            'message' => "Webinar successfully joined!"
        ], 200);
    }
}
