<?php
require_once "MysqlConn.php";
require_once "entity/Album.php";

function testInput($data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}

$nameErr = $artistsErr = $releaseDateErr = "";
$name = $artists = $releaseDate = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "This Segment must be filled!";
    } else {
        $name = testInput($_POST["name"]);
    }
    if (empty($_POST["artists"])) {
        $artistsErr = "This Segment must be filled!";
    } else {
        $artists = str_split(testInput($_POST["name"]), ",");
        foreach ($artists as $k => $v) {
            $artists[$k] = trim($v);
        }
    }
    if (empty($_POST["releaseDate"])) {
        $releaseDateErr = "This Segment must be filled!";
    } else {
        $releaseDate = testInput($_POST["releaseDate"]);
    }

    if (strlen($name) != 0 && strlen($artists) != 0 && strlen($releaseDate)) {
//        echo "artist :: " . $name . " " . $birthDate . " " . $sex . "\n";
        $album = new Album(0, $name,
            DateTime::createFromFormat("Y-m-d", $releaseDate));
        MysqlConn::getMysqlConnection()->addAlbum($album, $artists);
    }
}

?>

<form action="addAlbum.php" method="post">
    <h3>Album information Entry</h3>
    <br>
    Name:
    <label>
        <input type="text" name="name">
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
    <input type="submit" value="submit">
    <br>
    <p>
        <span id="addSong">
        </span>
    </p>
    <input type="button" value="Add a song" onclick="">
</form>

</body>
</html>