<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowedController;
use Inertia\Inertia;
use App\Http\Controllers\API\PassportAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('roles', RoleController::class);
    Route::resource('albums', AlbumController::class);
    Route::post('albums/{album}', [AlbumController::class, 'updateAlbum'])->name('updateAlbum');
    Route::resource('songs', SongController::class);
    Route::resource('playlists', PlaylistController::class);
    Route::post('playlists/{playlist}', [PlaylistController::class, 'updatePlaylist'])->name('updatePlaylist');
    Route::delete('/playlists/{id}', [PlaylistController::class, 'destroy']);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('premium', PremiumController::class);
    Route::resource('paypals', PaypalController::class);
    Route::resource('creditCards', CreditCardController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('likes', LikeController::class);
    Route::resource('followeds', FollowedController::class);
});
// Route::post('register', [PassportAuthController::class, 'register']);
// Route::post('login', [PassportAuthController::class, 'login']);
// Route::middleware('auth:api')->group(function () {

//     }
// );

require __DIR__ . '/auth.php';
