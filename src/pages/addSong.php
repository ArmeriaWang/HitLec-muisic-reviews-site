<?php
require_once "../MysqlConn.php";
require_once "../entity/Song.php";
require_once "../enum/Style.php";

$nameErr = $albumIdErr = $styleErr = $lengthErr = $artistsErr = "";
$name = $albumId = $style = $length = $artists = $artistArr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "This Segment must be filled!";
    } else {
        $name = testInput($_POST["name"]);
    }
    if (empty($_POST["albumId"])) {
        $albumIdErr = "This Segment must be filled!";
    } else {
        $albumId = testInput($_POST["albumId"]);
    }
    if (empty($_POST["style"])) {
        $styleErr = "This Segment must be filled!";
    } else {
        $style = testInput($_POST["style"]);
    }
    if (empty($_POST["length"])) {
        $lengthErr = "This Segment must be filled!";
    } else {
        $length = testInput($_POST["length"]);
    }
    if (empty($_POST["artists"])) {
        $artistsErr = "This Segment must be filled!";
    } else {
        $artists = $_POST["artists"];
        $artistArr = explode(",", testInput($artists));
        foreach ($artistArr as $k => $v) {
            $artistArr[$k] = trim($v);
        }
    }
    echo "song :: " . $name . " " . $albumId . " " . $style . " " . $length . " " . $artists . "<br>";
    if (strlen($name) && strlen($albumId) && strlen($style) && strlen($length) && strlen($artists)) {
        echo "song :: " . $name . " " . $albumId . " " . $style . " " . $length . " " . $artists . "<br>";
        $song = new Song(0, $name, $length, $albumId, new Style($style), $artistArr);
        MysqlConn::getMysqlConnection()->addSong($song);
    }
}

?>

<html lang="">
<head>
    <meta charset="utf-8">
    <title>MusRev - Add Song</title>
</head>
<body>

<h4><a href="../../index.php">Home Page</a></h4>
<h4><a href="viewSongs.php">Back to songs view</a></h4>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <h2> Song information Entry </h2>
    <br>
    Name:
    <label>
        <input type="text" name="name">
        <span class="error">* <?php echo $nameErr; ?></span>
    </label>
    <br><br>
    Style:
    <label>
        <select name="style">
            <option value="">Choose a style</option>
            <?php
            require_once "../utils.php";
            require_once "../enum/Style.php";
            echo array2multiChoice(Style::toArray());
            ?>
        </select>
        <span class="error">* <?php echo $styleErr; ?></span>
    </label>
    <br><br>
    AlbumId:
    <label>
        <input type="text" name="albumId">
        <span class="error">* <?php echo $albumIdErr; ?></span>
    </label>
    <br><br>
    TimeLength:
    <label>
        <input type="text" name="length">
        <span class="error">* <?php echo $lengthErr; ?></span>
    </label>
    <br><br>
    Artist (if multiple, separated by a single comma):
    <label>
        <input type="text" name="artists">
        <span class="error">* <?php echo $artistsErr; ?></span>
    </label>
    <br><br>
    <input type="submit" value="submit">
</form>

</body>
</html>
