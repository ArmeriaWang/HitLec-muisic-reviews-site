<html lang="">
<head>
    <meta charset="utf-8">
    <title>Add Album</title>
</head>
<body>

<form action="addAlbum.php" method="post">
    Album information Entry<br>
    Name:
    <label>
        <input type="text" name="name">
    </label>
    <br>
    Company:
    <label>
        <input type="text" name="company">
    </label>
    <br>
    Release Date:
    <label>
        <input type="date" name="releaseDate">
    </label>
    <br>
    Artist (if multiple, separated by a single comma):
    <label>
        <input type="text" name="artist">
    </label>
    <br>
    <input type="button" value="Add a song" onclick="">
    <input type="submit" value="submit">
</form>

</body>
</html>