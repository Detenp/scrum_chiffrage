<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class GamesAPIController extends Controller
{
    function getAllPlayersForGame(string $gameId) {
        return Player::with('game_id', $gameId)->get();
    }
}
