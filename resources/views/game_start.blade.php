<!DOCTYPE html>
<html>
    <form method="post" action="/join_game">
        @csrf
        <p>Nom: <input type="text" name="name"></p>
        <p>Code room: <input type="text" maxlength="5" name="room_code"></p>
        <input type="submit" value="Rejoindre">
    </form>
    <br>
    <br>
    <form method="post" action="/create_game">
        @csrf
        <p>Nom: <input type="text" name="name"></p>
        <p>Code room: <input type="text" maxlength="5" name="room_code"></p>
        <input type="submit" value="Créer une room">
    </form>
</html>