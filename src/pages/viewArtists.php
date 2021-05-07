<html lang="">

<head>
    <meta charset="utf-8">
    <title>MusRev - Artists</title>
</head>

<body>
<h2> View Artists </h2>
<h4><a href="../../index.php">Home Page</a></h4>
<form method="get" action="viewSingleArtist.php">
    <br>
    ArtistId:
    <label>
        <input type="text" name="artistId">
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
</form>

<?php
require_once "../MysqlConn.php";

$conn = MysqlConn::getMysqlConnection()->getMysqli();
$sql = "SELECT * FROM Artist";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["submit"] == "Filter") {
        if ($_POST["name"]) {
            $name = $_POST["name"];
            $sql = "SELECT * FROM Artist WHERE ArtistName like '%" . $name . "%';";
        }
    }
}
$result = mysqli_query($conn, $sql);

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
echo "</table>";

mysqli_close($conn);

?>

<br>
<a href="addArtist.php">
    <button>Add an Artist</button>
</a>

