<?php

use App\Http\Controllers\GameViewController;
use App\Http\Controllers\GamesAPIController;
use App\Http\Controllers\PlayersAPIController;
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

Route::get('players', [PlayersAPIController::class, 'getAll']);
Route::post('players', [PlayersAPIController::class, 'massUpdatePlayers']);
Route::delete('players/{id}', [PlayersAPIController::class, 'deletePlayerById']);
Route::post('players/{id}', [PlayersAPIController::class, 'updatePlayerById']);

Route::get('games/{gameId}/players', [GamesAPIController::class, 'getAllPlayersForGame']);
Route::get('games/{gameId}', [GamesAPIController::class, 'getGame']);
Route::delete('game/{gameId}', [GamesAPIController::class, 'deleteGame']);