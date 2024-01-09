<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Inertia\Inertia;
use Illuminate\Http\Request;
use getID3;

class SongController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:song-list|song-create|song-edit|song-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:song-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:song-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:song-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $songs = Song::all();
        return Inertia::render('userViews.Songs', ['songs' => $songs]);
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
            $request->validate([
                'file' => 'required|mimes:mp3|max:2048',
                'title' => 'required|string',
                'reproductions_number' => 'required|integer',
                'album_id' => 'required|exists:albums,id',
            ]);

            $song = new Song();
            $song->title = $request->title;
            $song->reproductions_number = $request->reproductions_number;
            $song->album_id = $request->album_id;

            $file = $request->file('file');
            $route = 'songs/users/';
            $fileName = time() . '-' . str_replace(' ', '', $file->getClientOriginalName());
            $request->file('file')->move($route, $fileName);
            $song->file = $fileName;

            // Obtener la duración del archivo MP3
            $getID3 = new \getID3();
            $fileInfo = $getID3->analyze($route . $fileName);
            $duration = round($fileInfo['playtime_seconds']);
            $song->duration = $duration;

            if ($song->save()) {
                return response()->json(['status' => 'OK', 'data' => $song], 201);
                return redirect('songs');
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
    public function show()
    {
        //
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
        try {
            $request->validate([
                'file' => 'sometimes|mimes:mp3|max:2048',
                'title' => 'sometimes|string',
                'reproductions_number' => 'sometimes|integer',
                'album_id' => 'sometimes|exists:albums,id',
            ]);

            $song = Song::findOrFail($id);

            // Actualizar los campos si se proporcionan en la solicitud
            $song->fill($request->only(['title', 'reproductions_number', 'album_id']));

            if ($request->hasFile('file')) {
                // Si se proporciona un nuevo archivo, actualizarlo y recalcular la duración
                $file = $request->file('file');
                $route = 'songs/users/';
                $fileName = time() . '-' . str_replace(' ', '', $file->getClientOriginalName());
                $request->file('file')->move($route, $fileName);
                $song->file = $fileName;

                // Obtener la duración del nuevo archivo MP3
                $getID3 = new \getID3();
                $fileInfo = $getID3->analyze($route . $fileName);
                $duration = round($fileInfo['playtime_seconds']);
                $song->duration = $duration;
            }

            if ($song->save()) {
                return response()->json(['status' => 'OK', 'data' => $song], 200);
                return redirect('songs');
            }
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $song = Song::FindORFail($request->id);
        $route = 'images/users/';
        unlink($route . $song->img);
        $song->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
        return redirect('songs');
    }
}
