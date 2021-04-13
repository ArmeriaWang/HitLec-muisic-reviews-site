<?php
require_once "MysqlConn.php";
require_once "entity/Listener.php";
require_once "enum/Sex.php";
require_once "utils.php";

function testInput($data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}

$songId = 0;
$song = null;
$artistsStr = $albumName = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $songId = testInput($_POST["songId"]);
    if (strlen($songId)) {
        echo "SongId :: " . $songId . "<br>";
        $results = MysqlConn::getMysqlConnection()->getSongById($songId);
        $song = $results[0];
        $albumName = $results[1];
        $artistsNames = $results[2];
        $artistsStr = array2string4artistsDisplay($artistsNames);
    }
}

?>

<html lang="">
<head>
    <meta charset="utf-8">
    <title>MusRev - Add Listener</title>
</head>
<body>

<h2> Single Song Information View </h2>
<br>
SongId: <?php echo $song == null ? "" : $song->getSongId(); ?>
<br>
Name: <?php echo $song == null ? "" :  $song->getName(); ?>
<br>
Artists: <?php echo $artistsStr; ?>
<br>
TimeLength: <?php echo $song == null ? "" :  $song->getTimeLength(); ?>
<br>
AlbumId: <?php echo $song == null ? "" :  $song->getAlbumId(); ?>
<br>
AlbumName: <?php echo $song == null ? "" :  $albumName; ?>
<br>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <input type="submit" value="submit">
</form>

</body>
</html>
