<!DOCTYPE html>
<html>
    <form method="post" action="/vote">
        @csrf
        <input type="text" name="name">
        <input type="submit" value="Rejoindre"/>
    </form>
</html>