<html lang="">

<head>
    <meta charset="utf-8">
    <title>MusRev - Songs</title>
</head>

<body>
<h2> View Songs </h2>
<h4><a href="../../index.php">Home Page</a></h4>
<form method="get" action="viewSingleSong.php">
    <br>
    SongId:
    <label>
        <input type="text" name="songId">
    </label>
    <input type="submit" name="go" value="Go">
</form>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    Name:
    <label>
        <input type="text" name="name">
    </label>
    <input type="submit" value="Filter" name="submit"> <br><br>
    Delete by SongId:
    <label>
        <input type="text" name="songId">
    </label>
    <input type="submit" value="Delete" name="submit">
</form>

<?php

require_once "../MysqlConn.php";

$conn = MysqlConn::getMysqlConnection()->getMysqli();
$sql = "SELECT * FROM SongsView;";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["submit"] == "Filter") {
        if ($_POST["name"]) {
            $name = $_POST["name"];
            $sql = "SELECT * FROM SongsView WHERE SongName like '%" . $name . "%';";
        }
    } else if ($_POST["submit"] == "Delete") {
        if ($_POST["songId"]) {
            $songId = $_POST["songId"];
            MysqlConn::getMysqlConnection()->deleteSongById($songId);
        }
    }
}
$result = mysqli_query($conn, $sql);

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

echo "<tbody id='songsTableContent'>";
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
echo "</tbody>";
echo "</table>";

?>

<br>
<a href="addSong.php">
    <button>Add a song</button>
</a>
<br><br>

</html>

