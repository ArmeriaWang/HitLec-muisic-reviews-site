<?php
//$q = isset($_GET["q"]) ? intval($_GET["q"]) : '';
//
//if(empty($q)) {
//    echo '请选择一个网站';
//    exit;
//}

require_once "MysqlConn.php";

$conn = MysqlConn::getMysqlConnection()->getMysqli();

$sql = "SELECT * FROM Song";

$result = mysqli_query($conn, $sql);

?>

<html lang="">

<head>
    <meta charset="utf-8">
    <title>MusRev - View Songs</title>
</head>

<body>
<form method="post" action="viewSingleSong.php">
    <h2> View Songs </h2>
    <br>
    SongId:
    <label>
        <input type="text" name="songId">
    </label>
    <br>
    <input type="submit" value="submit">
</form>

<?php
echo "<table border='1'>
    <tr>
        <th>SongId</th>
        <th>Name</th>
        <th>TimeLength</th>
        <th>Style</th>
    </tr>";

while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['SongId'] . "</td>";
    echo "<td>" . $row['Name'] . "</td>";
    echo "<td>" . $row['TimeLength'] . "</td>";
    echo "<td>" . $row['Style'] . "</td>";
    echo "</tr>";
}
echo "</table>";

mysqli_close($conn);
?>

</html>

