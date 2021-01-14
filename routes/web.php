<?php

use App\Http\Controllers\{
    DashboardController,
    ImageController,
    VideoController
};
use Illuminate\Support\Facades\Route;
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
    return view('homepage');
});
Route::get('/mail', function () {
    return new App\Mail\CreateConfession(['link'], 'Szut');
});

Route::get('/image/{img_id}/{access_code}', [ImageController::class, 'show']);
Route::get('/image/{img_id}', [ImageController::class, 'show']);
Route::get('/video/{img_id}/{access_code}', [VideoController::class, 'show']);
Route::get('/video/{img_id}', [VideoController::class, 'show']);

Route::get('/dashboard', [DashboardController::class, 'index']);
