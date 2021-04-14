<html lang="">

<head>
    <meta charset="utf-8">
    <title>MusRev - Songs</title>
</head>

<body>
<h2> View Songs </h2>
<h4><a href="index.php">Home Page</a></h4>
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
    <input type="submit" value="Filter">

</form>


<?php

require_once "MysqlConn.php";

$conn = MysqlConn::getMysqlConnection()->getMysqli();
$sql = "SELECT * FROM SongsView;";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["name"]) {
        $name = $_POST["name"];
        $sql = "SELECT * FROM SongsView WHERE SongName like '%" . $name . "%';";
    }
}
$result = mysqli_query($conn, $sql);

echo "<table border='1'>
    <tr>
        <th>SongId</th>
        <th>SongName</th>
        <th>TimeLength</th>
        <th>Style</th>
        <th>AlbumId</th>
        <th>AlbumName</th>
    </tr>";

echo "<tbody id='songsTableContent'>";
while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['SongId'] . "</td>";
    echo "<td>" . $row['SongName'] . "</td>";
    echo "<td>" . $row['TimeLength'] . "</td>";
    echo "<td>" . $row['Style'] . "</td>";
    echo "<td>" . $row['AlbumId'] . "</td>";
    echo "<td>" . $row['AlbumName'] . "</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";

mysqli_close($conn);
?>

</html>

