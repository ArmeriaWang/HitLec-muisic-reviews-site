<?php

require_once "../MysqlConn.php";

$conn = MysqlConn::getMysqlConnection()->getMysqli();

$sql4artist = "";
$sql4songs = "";
$sql4albums = "";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (array_key_exists('go', $_REQUEST)) {
        $artistId = testInput($_GET["artistId"]);
        if (strlen($artistId)) {
            echo "AlbumId :: " . $artistId . "<br>";
            $sql4artist = "SELECT * FROM Artist WHERE ArtistId = " . $artistId . ";";
            $sql4songs = "SELECT * FROM SongsView WHERE SongId IN (
                            SELECT DISTINCT SongId FROM MakeSong WHERE MakeSong.ArtistId = " . $artistId . ");";
            $sql4albums = "SELECT * FROM AlbumsView WHERE AlbumId IN (
                            SELECT DISTINCT AlbumId FROM MakeAlbum WHERE MakeAlbum.ArtistId = " . $artistId . ");";
        }
    }
}

?>
    <html lang="">
    <head>
        <meta charset="utf-8">
        <title>MusRev - Single Artist Information View</title>
    </head>
    <body>

    <h2> Single Artist Information View </h2>
    <h4><a href="../../index.php">Home Page</a></h4>
    <h4><a href="viewAlbums.php">Back to artists view</a></h4>
    <br>
<?php

$result = mysqli_query($conn, $sql4artist);
if ($result == false) {
//    $failAlert = "Invalid ArtistId!";
//    echo '<script>alert("' . $failAlert . '")</script>';
    header("Location: viewArtists.php");
    exit;
}
echo "<h4>Artist basic info:</h4>";
echo "<table border='1'>
<tr>
<th>ArtistId</th>
<th>ArtistName</th>
<th>BirthDate</th>
<th>Sex</th>
</tr>";
while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['ArtistId'] . "</td>";
    echo "<td>" . $row['ArtistName'] . "</td>";
    echo "<td>" . $row['BirthDate'] . "</td>";
    echo "<td>" . $row['Sex'] . "</td>";
    echo "</tr>";
}
echo "</table><br>";

$result = mysqli_query($conn, $sql4albums);
$row = mysqli_fetch_array($result);
echo "<h4>His/Her/Their/Its albums: </h4>";
echo "<table border='1'>
<tr>
<th>AlbumId</th>
<th>AlbumName</th>
<th>ReleaseDate</th>
<th>ArtistsIds</th>
<th>ArtistsNames</th>
<th>SongCnt</th>
</tr>";
while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['AlbumId'] . "</td>";
    echo "<td>" . $row['AlbumName'] . "</td>";
    echo "<td>" . $row['ReleaseDate'] . "</td>";
    echo "<td>" . $row['ArtistsIds'] . "</td>";
    echo "<td>" . $row['ArtistsNames'] . "</td>";
    echo "<td>" . $row['SongCnt'] . "</td>";
    echo "</tr>";
}
echo "</table><br>";


$result = mysqli_query($conn, $sql4songs);
$row = mysqli_fetch_array($result);
echo "<h4>His/Her/Their/Its songs: </h4>";
echo "<table border='1'>
    <tr>
        <th>SongId</th>
        <th>SongName</th>
        <th>TimeLength</th>
        <th>Style</th>
        <th>ArtistsIds</th>
        <th>ArtistsNames</th>
        <th>AlbumId</th>
        <th>AlbumName</th>
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
    echo "<td>" . $row['AlbumId'] . "</td>";
    echo "<td>" . $row['AlbumName'] . "</td>";
    echo "<td>" . $row['CommentCnt'] . "</td>";
    echo "</tr>";
}
echo "</table>";

mysqli_close($conn);
