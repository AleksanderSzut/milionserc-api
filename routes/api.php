<?php

use App\Http\Controllers\ConfessionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('order', [OrderController::class, 'create']);
Route::get('confessions', [OrderController::class, 'index']);
Route::post('transactionReturnUrl', [TransactionController::class, 'create']);
Route::get('confession/{uuid}/{access_code}', [ConfessionController::class, 'getConfession']);
Route::post('confession/{uuid}/{access_code}', [ConfessionController::class, 'create']);
Route::get('confessions', [OrderController::class, 'index']);

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact kontakt@milionserc.pl'], 404);
});
