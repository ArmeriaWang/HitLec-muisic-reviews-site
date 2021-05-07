<?php
require_once "../MysqlConn.php";
require_once "../entity/Listener.php";
require_once "../enum/Sex.php";
require_once "../utils.php";


$nameErr = "";
$name = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "This Segment must be filled!";
    } else {
        $name = testInput($_POST["name"]);
    }
    if (strlen($name)) {
        echo "listener :: " . $name .  "<br>";
        $listener = new Listener(0, $name);
        MysqlConn::getMysqlConnection()->addListener($listener);
    }
}

?>

<html lang="">
<head>
    <meta charset="utf-8">
    <title>MusRev - Add Listener</title>
</head>

<h4><a href="../../index.php">Home Page</a></h4>

<body>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <h2> Listener information Entry </h2>
    <br>
    Name:
    <label>
        <input type="text" name="name">
        <span class="error">* <?php echo $nameErr; ?></span>
    </label>
    <br><br>
    <input type="submit" value="submit">
</form>

</body>
</html>
