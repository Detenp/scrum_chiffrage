<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\Request;

class GameViewController extends Controller
{
    function manageGameView(Request $request) {
        $create_or_join = $request->input('join_or_create');

        if ($create_or_join == 'create') {
            return $this->createGame($request);
        } else if ($create_or_join == 'join') {
            return $this->joinGame($request);
        }
    }

    protected function joinGame(Request $request) {
        $room_code = $request->input('room_code');
        $name = $request->input('name');

        $game = Game::find($room_code);

        if (!$game) {
            return view('game_start');
        }

        $current_player = new Player();
        $current_player->name = $name;
        $current_player->vote = 0;
        $current_player->game_id = $game->id;

        $current_player->save();

        $players = Player::where('game_id', $game->id)->get();

        while($players->count() < 10) {
            $players->add(new Player());
        }

        return view('game', [
            'players' => $players,
            'current_player' => $current_player,
            'game' => $game
        ]);
    }

    protected function createGame(Request $request) {
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

        return view('game', [
                'players' => [$player],
                'current_player' => $player,
                'game' => $game
            ]);
    }
}
