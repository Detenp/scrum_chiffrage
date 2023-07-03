<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayersAPIController extends Controller
{
    function getAll() {
        return Player::all();
    }

    function deletePlayerById(int $id) {
        Player::destroy($id);

        return response('Player deleted.');
    }

    function updatePlayerById(Request $request, int $id) {
        $player = Player::find($id);

        if (!$player) {
            return response('Not found', 404);
        }

        $requestContent = json_decode($request->getContent(), true);

        $player_vote = $requestContent['vote'];

        $player->vote = $player_vote;

        $player->save();
    }

    function massUpdatePlayers(Request $request) {
        $requestContent = json_decode($request->getContent(), true);

        foreach($requestContent as $player) {
            $db_player = Player::find($player->id);

            if (!$db_player) {
                continue;
            }

            $db_player->vote = $player->vote;
            $db_player->save();
        }
    }
}
