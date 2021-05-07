<html lang="">

<head>
    <meta charset="utf-8">
    <title>MusRev - Albums</title>
</head>

<body>
<h2> View Albums </h2>
<h4><a href="../../index.php">Home Page</a></h4>
<form method="get" action="viewSingleAlbum.php">
    <br>
    AlbumId:
    <label>
        <input type="text" name="albumId">
    </label>
    <input type="submit" name="go" value="Go">
</form>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    Name:
    <label>
        <input type="text" name="name">
    </label>
    <input type="submit" value="Filter" name="submit"><br>
    <br>
    Delete by AlbumId:
    <label>
        <input type="text" name="albumId">
    </label>
    <input type="submit" value="Delete" name="submit">
</form>

<?php

require_once "../MysqlConn.php";

$conn = MysqlConn::getMysqlConnection()->getMysqli();
$sql = "SELECT * FROM AlbumsView";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["submit"] == "Filter") {
        if ($_POST["name"]) {
            $name = $_POST["name"];
            $sql = "SELECT * FROM AlbumsView WHERE AlbumName like '%" . $name . "%';";
        }
    } else if ($_POST["submit"] == "Delete") {
        if ($_POST["albumId"]) {
            $albumId = $_POST["albumId"];
            MysqlConn::getMysqlConnection()->deleteAlbumById($albumId);
        }
    }
}
$result = mysqli_query($conn, $sql);

echo "<table border='1'>
<tr>
<th>AlbumId</th>
<th>AlbumName</th>
<th>ReleaseDate</th>
<th>SongCnt</th>
<th>ArtistsIds</th>
<th>ArtistsNames</th>
</tr>";

while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['AlbumId'] . "</td>";
    echo "<td>" . $row['AlbumName'] . "</td>";
    echo "<td>" . $row['ReleaseDate'] . "</td>";
    echo "<td>" . $row['SongCnt'] . "</td>";
    echo "<td>" . $row['ArtistsIds'] . "</td>";
    echo "<td>" . $row['ArtistsNames'] . "</td>";
    echo "</tr>";
}
echo "</table>";

?>

<br>
<a href="addAlbum.php">
    <button>Add an Album</button>
</a>
