<?php
require_once "../MysqlConn.php";
require_once "../entity/Album.php";
require_once "../utils.php";

$nameErr = $artistsErr = $releaseDateErr = $artistsIds = "";
$name = $artists = $releaseDate = "";

//$addingSongs = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "This Segment must be filled!";
    } else {
        $name = testInput($_POST["name"]);
    }
    if (empty($_POST["artists"])) {
        $artistsErr = "This Segment must be filled!";
    } else {
        $artistsIds = $_POST["artists"];
        $artistsIdArr = explode(",", testInput($artistsIds));
        $artists = array();
        foreach ($artistsIdArr as $v) {
            array_push($artists, new Artist(trim($v)));
        }
    }
    if (empty($_POST["releaseDate"])) {
        $releaseDateErr = "This Segment must be filled!";
    } else {
        $releaseDate = testInput($_POST["releaseDate"]);
    }
    echo "album :: " . $name . " " . $releaseDate . "<br>";
    if (strlen($name) && strlen($artistsIds) && strlen($releaseDate)) {
        echo "album :: " . $name . " " . $releaseDate . "<br>";
        $album = new Album(0, $name,
            DateTime::createFromFormat("Y-m-d", $releaseDate),
            $artists, null);
        MysqlConn::getMysqlConnection()->addAlbum($album);
        header("Location: viewAlbums.php");
        exit;
    }

}

?>

<html lang="">
<head>
    <title>MisRev - Add Album</title>
</head>

<h4><a href="../../index.php">Home Page</a></h4>
<h4><a href="viewAlbums.php">Back to albums view</a></h4>

<body>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <h3>Album information Entry</h3>
    <br>
    <div id="albumInfo">
        Name
        <label>
            <input type="text" name="name">
            <span class="error">* <?php echo $nameErr; ?></span>
        </label>
        <br><br>
        Release Date
        <label>
            <input type="date" name="releaseDate">
            <span class="error">* <?php echo $releaseDateErr; ?></span>
        </label>
        <br><br>
        ArtistId
        <label>
            <input type="text" name="artists">
            *(if multiple, separated by a single comma)<br>
            <span class="error"> <?php echo $artistsErr; ?></span>
        </label>
        <br><br>
        <input type="submit" value="submit">
        <br>

    </div>
</form>

</body>
</html>