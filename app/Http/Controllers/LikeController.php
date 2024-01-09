<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        try {
            $like = new Like();
            $like->song_id = $request->song_id;
            $like->album_id = $request->album_id;

            if ($like->save() >= 1) {
                return response()->json(['status' => 'OK', 'data' => $like], 201);
            }
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $like = Like::get();
            return $like;
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // try {
        //     $like = Like::findOrFail($request->id);
        //     $like->song_id = $request->song_id;
        //     $like->album_id = $request->album_id;

        //     if ($like->save() >= 1) {
        //         return response()->json(['status' => 'OK', 'data' => $like], 201);
        //     }
        // } catch (\Exception $e) {
        //     return response()->json(["message" => $e->getMessage()], 500);
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $like = Like::FindORFail($request->id);
        $like->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
