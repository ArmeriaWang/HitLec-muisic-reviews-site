<?php
//$q = isset($_GET["q"]) ? intval($_GET["q"]) : '';
//
//if(empty($q)) {
//    echo '请选择一个网站';
//    exit;
//}

require_once "MysqlConn.php";

$conn = MysqlConn::getMysqlConnection()->getMysqli();

$sql = "SELECT * FROM AlbumsView";

$result = mysqli_query($conn, $sql);

echo "<table border='1'>
<tr>
<th>AlbumId</th>
<th>Name</th>
<th>ReleaseDate</th>
<th>SongCnt</th>
</tr>";

while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['AlbumId'] . "</td>";
    echo "<td>" . $row['Name'] . "</td>";
    echo "<td>" . $row['ReleaseDate'] . "</td>";
    echo "<td>" . $row['SongCnt'] . "</td>";
    echo "</tr>";
}
echo "</table>";

mysqli_close($conn);
