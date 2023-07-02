<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class Vote extends Controller
{
    function openPage(Request $request) {
        $name = $request->input('name');

        $players = Player::all();

        $current_player = new Player();
        $current_player->name = $name;
        $current_player->vote = 0;

        $current_player->save();

        $players->add($current_player);

        while($players->count() < 10) {
            $players->add(new Player());
        }

        return view('vote')
            ->with('players', $players)
            ->with('current_player', $current_player);
    }
}
