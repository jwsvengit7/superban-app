<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Superban;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/apply-ban/{userId}/{ipAddress}/{email}/{limit}/{timeInterval}/{banDuration}', 
function ($userId, $ipAddress, $email, $limit, $timeInterval, $banDuration) {
    $superban = new Superban();
    $superban->trackRequest($userId, $ipAddress, $email);
    $superban->applyBan($userId, $ipAddress, $email, $limit, $timeInterval, $banDuration);
    return response()->json(['message' => 'Ban applied successfully']);
});
