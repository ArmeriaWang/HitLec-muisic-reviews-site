<?php
//$q = isset($_GET["q"]) ? intval($_GET["q"]) : '';
//
//if(empty($q)) {
//    echo '请选择一个网站';
//    exit;
//}

require_once "MysqlConn.php";

$conn = MysqlConn::getMysqlConnection()->getMysqli();

$sql = "SELECT * FROM Artist";

$result = mysqli_query($conn, $sql);

echo "<table border='1'>
<tr>
<th>ArtistId</th>
<th>Name</th>
<th>BirthDate</th>
<th>Sex</th>
</tr>";

while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['ArtistId'] . "</td>";
    echo "<td>" . $row['Name'] . "</td>";
    echo "<td>" . $row['BirthDate'] . "</td>";
    echo "<td>" . $row['Sex'] . "</td>";
    echo "</tr>";
}
echo "</table>";

mysqli_close($conn);
