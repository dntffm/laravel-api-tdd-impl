<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Article::with(['image'])->orderBy('created_at', 'desc')->get();

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
            'article_title' => 'required',
            'article_content' => 'required',
        ]);

        if($validated->fails()) {
            return $this->sendError($validated->errors(), 422);
        }

        $request->merge(['created_by' => Auth::user()->id]);
        $article = Article::create($request->except('thumbnail'));

        if($request->has('thumbnail')) {
            $thumbnail = $request->file('thumbnail')->storeAs('public/article', time().$request->file('thumbnail')->getClientOriginalName());
            $at = $article->image()->create([
                'url' => $thumbnail,
                'type' => 'image'
            ]);

            $article->update([
                'thumbnail' => $at->id
            ]);
        }

        return $this->sendResponse('Article successfully created!', $article);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id  $article
     * @return \Illuminate\Http\Response
     */
    public function show($article)
    {
        $data = Article::with(['image'])->findOrFail($article);

        return $this->sendResponse('Fetched succesfully!', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $article)
    {
        $article = Article::find($article);

        if(!$article) {
            return $this->sendError('Article not found!', 404);
        }

        $article->update($request->except('thumbnail'));

        if($request->has('thumbnail')) {
            $thumbnail = $request->file('thumbnail')->storeAs('public/article', time().$request->file('thumbnail')->getClientOriginalName());
            $at = $article->image()->create([
                'url' => $thumbnail,
                'type' => 'image'
            ]);

            $article->update([
                'thumbnail' => $at->id
            ]);
        }

        return $this->sendResponse('Article successfully updated!', $article->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy($article)
    {
        $article = Article::find($article);

        if(!$article) {
            return $this->sendError('Article not found!', 404);
        }

        $article->delete();

        return $this->sendResponse('Article successfully deleted!');
    }
}
