<!DOCTYPE html>
<head>
    <script
			  src="https://code.jquery.com/jquery-3.7.0.min.js"
			  integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g="
			  crossorigin="anonymous"></script>

    <script>
        var activePlayers = [];
        var game = {};

        setInterval(function() {
            updateActivePlayers();
            updateGame();
        }, 3000);

        function updateActivePlayers() {
            $.ajax({
                type: "GET",
                url: "http://localhost:8000/api/games/{{ $game->id }}/players",
            }).done(function(data) {
                activePlayers = data;
                updateShownPlayers();
                updateAverage();
            }).fail(function(data, err) {
                console.log("fail " + JSON.stringify(data));
            });
        }

        function updateShownPlayers() {
            var activePlayersIds = [];

            for (var i = 0; i < activePlayers.length; i++) {
                activePlayersIds.push(activePlayers[i].id);
            }

            var currentShownPlayersIds = getShownPlayersIds();

            var playersThatShouldBeRemoved = currentShownPlayersIds.filter(x => !activePlayersIds.includes(x));
            var playersThatShouldBeAdded = activePlayersIds.filter(x => !currentShownPlayersIds.includes(x));
            
            for (var i = 0; i < playersThatShouldBeRemoved.length; i++) {
                $('[name="player_' + playersThatShouldBeRemoved[i] + '"]').remove();
            }

            var playersList = $('#playersList');

            for (var i = 0; i < playersThatShouldBeAdded.length; i++) {
                var playerToAdd = activePlayers.find(e => e.id == playersThatShouldBeAdded[i]);

                var divToAdd = printPlayerDiv(playerToAdd, !game.reveal);

                playersList.append(divToAdd);
            }
        }

        function updateGame() {
            $.ajax({
                type: "GET",
                url: "http://localhost:8000/api/games/{{ $game->id }}",
            }).done(function(data) {
                game = data;
                console.log(game);
            }).fail(function(data, err) {
                console.log("fail " + JSON.stringify(data));
            });
        }

        function updateVotesReveal() {
            game.reveal = !game.reveal
            $.ajax({
                type: "POST",
                url: "http://localhost:8000/api/games/{{ $game->id }}",
                data: game
            }).done(function(data) {
                if (game.reveal) {
                    revealVotes();
                } else {
                    hideVotes();
                }
            }).fail(function(data, err) {
                console.log("fail " + JSON.stringify(data));
            });
        }

        function revealVotes() {
            var otherPlayersVotes = $('#foreign_player_vote');

            for (var i = 0; i < otherPlayersVotes.length; i++) {
                otherPlayersVotes[i].removeAttribute('hidden');
            }
            $("#total_average").attr('hidden', false);

            $('#reveal_votes').val('Hide votes');
        }

        function hideVotes() {
            var otherPlayersVotes = $('#foreign_player_vote');

            for (var i = 0; i < otherPlayersVotes.length; i++) {
                otherPlayersVotes[i].hidden = true;
            }
            $("#total_average").attr('hidden', true);

            $('#reveal_votes').val('Reveal votes');
        }

        function updateAverage() {
            var average = 0;

            for (var i = 0; i < activePlayers.length; i++) {
                average += activePlayers[i].vote;
            }

            average = average / activePlayers.length;

            $("#total_average").text(average);
        }

        function resetVotes() {
            updateActivePlayers();

            var load = []
            for (var i = 0; i < activePlayers.length; i++) {
                activePlayers[i].vote = 0;
                load.push(JSON.stringify(activePlayers[i]))
            }

            $.ajax({
                type: "POST",
                url: "http://localhost:8000/api/players",
                data: load
            }).done(function(data) {
                updateActivePlayers();
                updateShownPlayers();
                updateAverage();
            }).fail(function(data, err) {
                console.log("fail " + JSON.stringify(data));
            });
        }

        function getShownPlayersIds() {
            var currentShownPlayersIds = []

            var playersListJquery = $("#playersList").find('div')

            for(var i = 0; i < playersListJquery.length; i++) {
                var player_id = $(playersListJquery[i]).attr('name').match(/\d+/)[0]
                currentShownPlayersIds.push(player_id);
            }

            return currentShownPlayersIds;
        }

        function playerVote() {
            var voteValue = $('#player_vote').val();

            $.ajax({
                type: "POST",
                async: false,
                url: "http://localhost:8000/api/players/" + {{ $current_player->id }},
                data: '{"vote": ' + voteValue + '}'
            }).done(function(data) {
                console.log(data);
            }).fail(function(data, err) {
                console.log("fail " + JSON.stringify(data));
            });
        }

        function printPlayerDiv(playerToAdd, isHidden) {
            var toPrint = '<div name="player_' + playerToAdd.id + '"><p>name: ' +  playerToAdd.name + '</p><p id="foreign_player_vote"';
            toPrint += isHidden ? 'hidden="true"' : "";
            toPrint += '>vote: ' + playerToAdd.vote + '</p></div>'

            return toPrint;
        }

        window.addEventListener("beforeunload", function (e) {
            $.ajax({
                type: "DELETE",
                async: false,
                url: "http://localhost:8000/api/players/" + {{ $current_player->id }},
            }).done(function(data) {
                console.log(data);
            }).fail(function(data, err) {
                console.log("fail " + JSON.stringify(data));
            });
        });
    </script>
</head>
<html>
    <body>
        <div id="currentPlayer">
            <h1>Current Player: {{ $current_player->name }}</h1>
            <h2>Room code: {{ $game->id }}</h2>
            <input type="text" name="player_vote" id="player_vote">
            <input type="submit" name="player_submit" value="Vote" onclick="playerVote()">
            <br>
            @if ($current_player->id == $game->game_master)
                <input type="submit" name="reset_votes" value="Reset Votes" onclick="resetVotes()">
                <input type="submit" id="reveal_votes" name="reveal_votes" value="Reveal Votes" onclick="updateVotesReveal()">
                <input type="submit" name="next_note" value="Next Note">
            @endif
            <p>Votes average:</p>
            <p id="total_average">0</p>
        </div>
        <div id="playersList">
            @foreach ($players as $player)
                @if ($player->id)
                    <div name="player_{{ $player->id }}">
                        <p>name: {{ $player->name }}</p>
                        <p id="foreign_player_vote" hidden="{{ $game->reveal }}">vote: {{ $player->vote }}</p>
                    </div>
                @endif
            @endforeach
        </div>
    </body>
</html>