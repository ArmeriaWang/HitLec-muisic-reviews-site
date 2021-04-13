<?php
require_once "MysqlConn.php";
require_once "entity/Album.php";

function testInput($data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}

$nameErr = $artistsErr = $releaseDateErr = $artistArr = "";
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
        $artists = $_POST["artists"];
        echo "artists = " . $artists . "<br>";
        $artistArr = explode(",", testInput($artists));
        foreach ($artistArr as $k => $v) {
            $artistArr[$k] = trim($v);
        }
    }
    if (empty($_POST["releaseDate"])) {
        $releaseDateErr = "This Segment must be filled!";
    } else {
        $releaseDate = testInput($_POST["releaseDate"]);
    }
    echo "album :: " . $name . " " . $releaseDate . "<br>";
    if (strlen($name) && strlen($artists) && strlen($releaseDate)) {
        echo "album :: " . $name . " " . $releaseDate . "<br>";
        $album = new Album(0, $name,
            DateTime::createFromFormat("Y-m-d", $releaseDate));
        MysqlConn::getMysqlConnection()->addAlbum($album, $artistArr);
    }

}

?>

<html lang="">
<head>
    <title>MisRev - Add Album</title>
<!--    <script>
        function getAlbumInfo() {
            let xmlHttp = new XMLHttpRequest();
            let info = document.getElementById("albumInfo");
            info.getAttribute("name");
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
                    document.getElementById("albumInfo").innerHTML = xmlHttp.responseText;
                }
            }
            xmlHttp.open("GET", "getLatest.php?");
        }
    </script>-->
</head>

<body>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <h3>Album information Entry</h3>
    <br>
    <div id="albumInfo">
        Name:
        <label>
            <input type="text" name="name">
            <span class="error">* <?php echo $nameErr; ?></span>
        </label>
        <br>
        Release Date:
        <label>
            <input type="date" name="releaseDate">
            <span class="error">* <?php echo $releaseDateErr; ?></span>
        </label>
        <br>
        Artist (if multiple, separated by a single comma):
        <label>
            <input type="text" name="artists">
            <span class="error">* <?php echo $artistsErr; ?></span>
        </label>
        <br>
        <input type="submit" value="submit">
        <br>

    </div>
</form>

</body>
</html>