<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use Inertia\Inertia;

class AlbumController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:album-list|album-create|album-edit|album-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:album-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:album-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:album-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $albums = Album::with('user')->get();
        return Inertia::render('userViews/Albums', ['albums' => $albums]);
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
            $year = date('Y');
            $album = new Album();
            $album->title = $request->title;
            $album->year = $year;
            $request->validate(['img' => 'image|max:2048']);
            $img = $request->file('img');
            $route = 'images/users/';
            $imgName = time() . '-' . str_replace(' ', '', $img->getClientOriginalName());
            $request->file('img')->move($route, $imgName);
            $album->img = $imgName;
            $album->user_id = $user->id;

            if ($album->save() >= 1) {
                return response()->json(['status' => 'OK', 'data' => $album], 201);
                return redirect('albums');
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
        try {
            $album = Album::get();
            return response()->json(['status' => 'OK', 'data' => $album], 201);
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
    public function updateAlbum(Request $request, $id)
    {
        try {
            // dd($request->all());
            $user = auth()->user();
            $year = date('Y');

            // Buscar el álbum existente
            $album = Album::findOrFail($id);
            if ($request->hasFile('img')) {
                // Validar y procesar la nueva imagen
                $request->validate(['img' => 'image|max:2048']);
                $img = $request->file('img');
                $route = 'images/users/';
                $imgName = time() . '-' . str_replace(' ', '', $img->getClientOriginalName());
                
                // Mover el archivo antes de asignar el nombre al modelo
                $img->move($route, $imgName);
                
                // Eliminar la imagen anterior
                unlink($route . $album->img);
                
                // Asignar el nombre del archivo al modelo
                $album->title = $request->title;
                $album->year = $year;
                $album->img = $imgName;
                $album->user_id = $user->id;
            }
            if ($album->save() >= 1) {
                return response()->json(['status' => 'OK', 'data' => $album], 201);
                return redirect('albums');
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(["message" => "No se encontró el álbum"], 404);
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
        $album = Album::findOrFail($id);
        $route = 'images/users/';
        unlink($route . $album->img);
        $album->delete();

        return redirect('albums')->with('success', 'Album deleted successfully');
    }
}
