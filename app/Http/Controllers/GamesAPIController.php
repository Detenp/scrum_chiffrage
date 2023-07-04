<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\Request;

class GamesAPIController extends Controller
{
    function getAllPlayersForGame(string $gameId) {
        return Player::where('game_id', $gameId)->get();
    }

    function getGame(string $gameId) {
        return Game::find($gameId);
    }

    function deleteGame(string $gameId) {
        Game::destroy($gameId);
    }
}
