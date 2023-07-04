<!DOCTYPE html>
<html>
    <form method="get" action="/game">
        @csrf
        <p>Nom: <input type="text" name="name"></p>
        <p>Code room: <input type="text" maxlength="5" name="room_code"></p>
        <input type="hidden" value="join" name="join_or_create">
        <input type="submit" value="Rejoindre">
    </form>
    <br>
    <br>
    <form method="get" action="/game">
        @csrf
        <p>Nom: <input type="text" name="name"></p>
        <p>Code room: <input type="text" maxlength="5" name="room_code"></p>
        <input type="hidden" value="create" name="join_or_create">
        <input type="submit" value="Créer une room">
    </form>
</html>