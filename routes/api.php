<?php

use App\Http\Controllers\API\TimeSchedulingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('services', [TimeSchedulingController::class, 'index']);
Route::post('services', [TimeSchedulingController::class, 'bookSlot']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
