<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\Request;

class GameController extends Controller
{
    function joinGame(Request $request) {
        $room_code = $request->input('room_code');
        $name = $request->input('name');

        $game = Game::find($room_code);

        if (!$game) {
            return view('game_start');
        }

        $players = Player::all();

        $current_player = new Player();
        $current_player->name = $name;
        $current_player->vote = 0;

        $current_player->save();

        $players->add($current_player);

        while($players->count() < 10) {
            $players->add(new Player());
        }

        return view('game')
            ->with('players', $players)
            ->with('current_player', $current_player)
            ->with('game', $game);
    }

    function createGame(Request $request) {
        $room_code = $request->input('room_code');
        $name = $request->input('name');

        $game = Game::find($room_code);
        if ($game) {
            return view('game_start');
        }

        $game = new Game();
        $game->id = $room_code;
        
        $game->save();

        $player = new Player();
        $player->name = $name;
        $player->vote = 0;
        $player->game_id = $room_code;

        $player->save();

        $game->game_master = $player->id;

        $game->save();

        return view('game')
            ->with('players', [$player])
            ->with('current_player', $player)
            ->with('game', $game);
    }
}
