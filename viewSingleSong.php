<?php
require_once "MysqlConn.php";
require_once "entity/Listener.php";
require_once "entity/Comment.php";
require_once "enum/Sex.php";
require_once "utils.php";

$songId = 0;
$song = null;
$artistsStr = $albumName = "";

$listenerIdErr = $listenerId = "";
$commentErr = $comment = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (array_key_exists('go', $_REQUEST)) {
        $songId = testInput($_GET["songId"]);
        if (strlen($songId)) {
            echo "SongId :: " . $songId . "<br>";
            $results = MysqlConn::getMysqlConnection()->getSongById($songId);
            $song = $results[0];
            $albumName = $results[1];
            $artistsStr = array2string4display($song->getArtistsIds());
        }
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $songId = testInput($_GET["songId"]);
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
    <title>MusRev - Add Listener</title>
</head>
<body>

<h2> Single Song Information View </h2>
<h4><a href="index.php">Home Page</a></h4>
<h4><a href="viewSongs.php">Back to songs view</a></h4>
<br>

SongId: <?php echo $song == null ? "" : $song->getSongId(); ?>
<br>
SongName: <?php echo $song == null ? "" : $song->getSongName(); ?>
<br>
Artists: <?php echo $artistsStr; ?>
<br>
TimeLength: <?php echo $song == null ? "" : $song->getTimeLength(); ?>
<br>
AlbumId: <?php echo $song == null ? "" : $song->getAlbumId(); ?>
<br>
AlbumName: <?php echo $song == null ? "" : $albumName; ?>
<br>


<?php

$comments = MysqlConn::getMysqlConnection()->getCommentsBySongId($songId);
foreach ($comments as $comment) {
    echo "<hr width='300' align='left'>";
    $listenerId = $comment->getListenerId();
    echo "CommentId: " . $comment->getCommentId() . "<br>";
    echo "ListenerId: " . $listenerId . "<br>";
    echo "ListenerName: " . MysqlConn::getMysqlConnection()->getListenerById($listenerId)->getName() . "<br>";
    echo "Content: " . $comment->getContent() . "<br>";
}

?>

<hr width='300' align='left'>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
    <h5>Leave your comment here...</h5>
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
