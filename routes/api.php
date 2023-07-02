<?php

use App\Http\Controllers\Players;
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
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('players', [Players::class, 'getAll']);
Route::post('players', [Players::class, 'massUpdatePlayers']);
Route::delete('players/{id}', [Players::class, 'deletePlayerById']);
Route::post('players/{id}', [Players::class, 'updatePlayerById']);
