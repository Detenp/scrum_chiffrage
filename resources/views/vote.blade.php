<!DOCTYPE html>
<head>
    <script
			  src="https://code.jquery.com/jquery-3.7.0.min.js"
			  integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g="
			  crossorigin="anonymous"></script>

    <script>
        var activePlayers = []

        setInterval(function() {
            updateActivePlayers();
            updateShownPlayers();
            updateAverage();
        }, 3000);

        function updateActivePlayers() {
            $.ajax({
                type: "GET",
                url: "http://localhost:8000/api/players",
            }).done(function(data) {
                activePlayers = data;
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

                var divToAdd = '<div name="player_' + playerToAdd.id + '"><p>name: ' +  playerToAdd.name + '</p><p>vote: ' + playerToAdd.vote + '</p></div>'

                playersList.append(divToAdd);
            }
        }

        function updateAverage() {
            var average = 0;

            for (var i = 0; i < activePlayers.length; i++) {
                average += activePlayers[i].vote;
            }

            average = average / activePlayers.length;

            console.log("average: " + average);

            $("#total_average").text(average);
        }

        function resetVotes() {
            updateActivePlayers();

            for (var i = 0; i < activePlayers.length; i++) {
                activePlayers[i].vote = 0;
            }

            $.ajax({
                type: "POST",
                async: false,
                url: "http://localhost:8000/api/players,
                data: activePlayers
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
            <input type="text" name="player_vote" id="player_vote">
            <input type="submit" name="player_submit" value="Vote" onclick="playerVote()">
            <br>
            <input type="submit" name="reset_votes" value="Reset Votes">
            <input type="submit" name="reveal_votes" value="Reveal Votes">
            <p>Votes average:</p>
            <p id="total_average">0</p>
        </div>
        <div id="playersList">
            @foreach ($players as $player)
                @if ($player->id)
                    <div name="player_{{ $player->id }}">
                        <p>name: {{ $player->name }}</p>
                        <p>vote: {{ $player->vote }}</p>
                    </div>
                @endif
            @endforeach
        </div>
    </body>
</html>