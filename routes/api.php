<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PassportAuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowedController;


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
//         Route::resource('playlists', PlaylistController::class);
//         Route::resource('songs', SongController::class);
//         Route::resource('users', UserController::class);
//         Route::resource('premium', PremiumController::class);
//         Route::resource('paypals', PaypalController::class);
//         Route::resource('creditCards', CreditCardController::class);
//         Route::resource('payments', PaymentController::class);
//         Route::resource('likes', LikeController::class);
//         Route::resource('followeds', FollowedController::class);
        

//     }
// );
