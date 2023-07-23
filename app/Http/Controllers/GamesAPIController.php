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

    function postGame(Request $request, string $gameId) {
        $game = Game::find($gameId);

        if (!$game) {
            return response('Not found', 404);
        }

        $requestContent = $request->all();

        

        if (key_exists('reveal', $requestContent)) {
            $should_reveal = filter_var($requestContent['reveal'], FILTER_VALIDATE_BOOLEAN);
            $game->reveal = $should_reveal;
        }

        $game->save();
    }
}
