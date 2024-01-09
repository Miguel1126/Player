<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $playlistsActive = Playlist::with('songs', 'user')->withCount('songs')->get();
            $playlistsDeleted = Playlist::onlyTrashed()->with('songs', 'user')->withCount('songs')->get();

            return Inertia::render('userViews/Playlists', [
                'playlistsActive' => $playlistsActive,
                'playlistsDeleted' => $playlistsDeleted,
            ]);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
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
            $user = auth()->user();
            $date = now(); // Usando el helper now() para obtener la fecha y hora actual
            $playlist = new Playlist();
            $playlist->title = $request->title;
            $playlist->creation_date = $date;
            $playlist->user_id = $user->id;
    
            if ($playlist->save()) {
                // Adjuntar múltiples canciones a la playlist
                $playlist->songs()->attach($request->song_ids);
    
                return response()->json(['status' => 'OK', 'data' => $playlist], 201);
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
    // public function showD()
    // {
    //     $playlist = Playlist::with('songs', 'user')->withCount('songs')->onlyTrashed()->get();
    //     return Inertia::render('userViews/Playlists', ['playlists' => $playlist]);
    // }

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
    public function updatePlaylist(Request $request, $id)
    {
        try {
            $user = auth()->user();
            $date = now();
            $playlist = Playlist::findOrFail($id);
            $playlist->title = $request->title;
            $playlist->creation_date = $date;
            $playlist->user_id = $user->id;

            if ($playlist->save() >= 1) {
                $playlist->songs()->attach($request->song_id);
                return response()->json(['status' => 'OK', 'data' => $playlist], 201);
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
    public function destroy($id)
    {
        try {
            $playlist = Playlist::findOrFail($id);

            // Cambia el estado a 'D' antes de eliminar
            $playlist->update(['state' => 'D']);

            // Soft delete (opcional, dependiendo de tus necesidades)
            $playlist->delete();

            return response()->json(['message' => 'Playlist eliminada con éxito']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
