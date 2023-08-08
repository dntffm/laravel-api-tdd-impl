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
        $validated = Validator::make($request->all(), [
            "webinar_name" => "required",
            "description" => "required",
            "price" => "required",
            "link" => "required",
            "started_at" => "required",
            "register_end_at" => "required",
        ]);

        if($validated->fails()) {
            return $this->sendError($validated->errors(), 422);
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
            return $this->sendResponse("Webinar successfully created!", $webinar);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
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
            return $this->sendError('Webinar Not Found!', 404);
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

        return $this->sendResponse("Webinar successfully updated!", $webinar->fresh());
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
            return $this->sendError('Webinar Not Found!', 404);
        }

        $webinarChosen->destroy($webinar);

        return $this->sendResponse("Webinar successfully deleted!");
    }

    public function joinWebinar(Request $request)
    {
        $user = Auth::user();
        $webinar = $request->webinar_id;
        $webinarChosen = Webinar::where('id', $webinar)->first();

        if(!$webinarChosen) {
            return $this->sendError('Webinar Not Found!', 404);
        }


        if($webinarChosen->price < 1) {
            $user->agendas()->attach($webinar);

            return $this->sendResponse("Webinar successfully joined!");
        }
        //PAYMENT GATEWAY SERVICE
        $user->agendas()->attach($webinar);

        return $this->sendResponse("Webinar successfully joined!");
    }
}
