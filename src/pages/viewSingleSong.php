<?php
require_once "../MysqlConn.php";
require_once "../entity/Listener.php";
require_once "../entity/Comment.php";
require_once "../enum/Sex.php";
require_once "../utils.php";

$conn = MysqlConn::getMysqlConnection()->getMysqli();

$songId = '';
$song = null;
$artistsIds = $artistsNames = $albumName = "";
$sql4song = '';

$listenerIdErr = $listenerId = "";
$commentErr = $comment = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (array_key_exists('go', $_REQUEST)) {
        $songId = testInput($_GET["songId"]);
        if (strlen($songId)) {
            $sql4song = "SELECT * FROM SongsView WHERE AlbumId = " . $songId . ";";
        }
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $songId = testInput($_GET["songId"]);
    if (strlen($songId)) {
        $sql4song = "SELECT * FROM SongsView WHERE AlbumId = " . $songId . ";";
    }
    if (empty($_POST["listenerId"])) {
        $listenerIdErr = "This segment must be filled!";
    } else {
        $listenerId = testInput($_POST["listenerId"]);
    }
    if (empty($_POST["comment"])) {
        $commentErr = "This Segment must be filled!";
    } else {
        $comment = testInput($_POST["comment"]);
    }
    if (strlen($listenerId) && strlen($comment)) {
        MysqlConn::getMysqlConnection()->addComment(new Comment(0, $listenerId, $songId, $comment));
    }
}

?>

<html lang="">
<head>
    <meta charset="utf-8">
    <title>MusRev - Single Song Information View</title>
</head>
<body>

<h2> Single Song Information View </h2>
<h4><a href="../../index.php">Home Page</a></h4>
<h4><a href="viewSongs.php">Back to songs view</a></h4>
<br>

<?php
$result = mysqli_query($conn, $sql4song);
if ($result == false) {
    header("Location viewSongs.php");
    exit;
}
$row = mysqli_fetch_array($result);
echo "<h4>Song basic info: </h4>";
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
    </tr>";
echo "<tr>";
echo "<td>" . $row['SongId'] . "</td>";
echo "<td>" . $row['SongName'] . "</td>";
echo "<td>" . $row['TimeLength'] . "</td>";
echo "<td>" . $row['Style'] . "</td>";
echo "<td>" . $row['ArtistsIds'] . "</td>";
echo "<td>" . $row['ArtistsNames'] . "</td>";
echo "<td>" . $row['AlbumId'] . "</td>";
echo "<td>" . $row['AlbumName'] . "</td>";
echo "</tr>";
echo "</table>";

$comments = MysqlConn::getMysqlConnection()->getCommentsBySongId($songId);
echo "<br><h4>Total " . $row['CommentCnt'] . ' comments:</h4>';
foreach ($comments as $comment) {
    echo "<hr width='300' align='left'>";
    $listenerId = $comment->getListenerId();
    echo "<i>CommentId:</i>    " . $comment->getCommentId() . ", ";
    echo "<i>ListenerId:</i>   " . $listenerId . ", ";
    echo "<i>ListenerName:</i> " . MysqlConn::getMysqlConnection()->getListenerById($listenerId)->getName() . "<br>";
    echo "<i>Content:</i>      " . $comment->getContent() . "<br>";
}

?>

<hr width='300' align='left'>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
    <h4>Leave your comment here...</h4>
    ListenerId
    <label>
        <input type="text" name="listenerId">
        <span class="error">* <?php echo $listenerIdErr; ?></span>
    </label>
    <br>
    <label>
        <textarea name="comment" placeholder="Edit your comment here..." cols="30" rows="5"></textarea>
        <span class="error">* <?php echo $commentErr; ?></span>
    </label>
    <br>
    <input type="submit" name="commentSubmit" value="submit">
</form>

</body>
</html>
