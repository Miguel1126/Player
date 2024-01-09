<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PassportAuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\PlaylistController;
use App\Models\Playlist;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::post('register', [PassportAuthController::class, 'register']);
// Route::post('login', [PassportAuthController::class, 'login']);
// Route::middleware('auth:api')->group(function () {
//         Route::post('logout', [PassportAuthController::class, 'logout']);
//         Route::resource('roles', RoleController::class);
//         Route::resource('albums', AlbumController::class);
//         Route::resource('songs', SongController::class);
//         Route::resource('playlists', PlaylistController::class);
//         // Route::get('/get/playlists', [PlaylistController::class, 'show']);

//     }
// );
