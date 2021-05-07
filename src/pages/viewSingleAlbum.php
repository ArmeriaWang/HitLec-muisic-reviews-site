<?php
//$q = isset($_GET["q"]) ? intval($_GET["q"]) : '';
//
//if(empty($q)) {
//    echo '请选择一个网站';
//    exit;
//}

require_once "../MysqlConn.php";

$conn = MysqlConn::getMysqlConnection()->getMysqli();

$sql4album = "";
$sql4songs = "";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (array_key_exists('go', $_REQUEST)) {
        $albumId = testInput($_GET["albumId"]);
        if (strlen($albumId)) {
            echo "AlbumId :: " . $albumId . "<br>";
            $sql4album = "SELECT * FROM AlbumsView WHERE AlbumId = " . $albumId . ";";
            $sql4songs = "SELECT * FROM SongsView WHERE AlbumId = " . $albumId . ";";
        }
    }
}
$result = mysqli_query($conn, $sql4album);
if ($result == false) {
    $failAlert = "Invalid AlbumId!";
    echo '<script>alert("' . $failAlert . '")</script>';
}
?>
    <html lang="">
    <head>
        <meta charset="utf-8">
        <title>MusRev - Single Album Information View</title>
    </head>
    <body>

    <h2> Single Album Information View </h2>
    <h4><a href="../../index.php">Home Page</a></h4>
    <h4><a href="viewAlbums.php">Back to albums view</a></h4>
    <br>

<?php

echo "<table border='1'>
<tr>
<th>AlbumId</th>
<th>AlbumName</th>
<th>ReleaseDate</th>
<th>ArtistsIds</th>
<th>ArtistsNames</th>
</tr>";

$row = mysqli_fetch_array($result);
echo "<h4>Basic Info: </h4>";
echo "<tr>";
echo "<td>" . $row['AlbumId'] . "</td>";
echo "<td>" . $row['AlbumName'] . "</td>";
echo "<td>" . $row['ReleaseDate'] . "</td>";
echo "<td>" . $row['ArtistsIds'] . "</td>";
echo "<td>" . $row['ArtistsNames'] . "</td>";
echo "</tr>";
echo "</table><br>";

echo "<h4>Total " . $row['SongCnt'] . ' songs: </h4>';


$result = mysqli_query($conn, $sql4songs);
echo "<table border='1'>
    <tr>
        <th>SongId</th>
        <th>SongName</th>
        <th>TimeLength</th>
        <th>Style</th>
        <th>ArtistsIds</th>
        <th>ArtistsNames</th>
        <th>CommentCnt</th>
    </tr>";

while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['SongId'] . "</td>";
    echo "<td>" . $row['SongName'] . "</td>";
    echo "<td>" . $row['TimeLength'] . "</td>";
    echo "<td>" . $row['Style'] . "</td>";
    echo "<td>" . $row['ArtistsIds'] . "</td>";
    echo "<td>" . $row['ArtistsNames'] . "</td>";
    echo "<td>" . $row['CommentCnt'] . "</td>";
    echo "</tr>";
}
echo "</table>";

mysqli_close($conn);
