<?php
require_once "MysqlConn.php";
require_once "entity/Artist.php";
require_once "enum/Sex.php";
require_once "utils.php";


$nameErr = $sexErr = "";
$name = $birthDate = $sex = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "This Segment must be filled!";
    } else {
        $name = testInput($_POST["name"]);
    }
    $birthDate = testInput($_POST["birthDate"]);
    if (empty($_POST["sex"])) {
        $sexErr = "This Segment must be filled!";
    } else {
        $sex = testInput($_POST["sex"]);
    }
    if (strlen($name) != 0 && strlen($sex) != 0) {
//        echo "artist :: " . $name . " " . $birthDate . " " . $sex . "<br>";
        $artist = new Artist(0, $name,
            DateTime::createFromFormat("Y-m-d", $birthDate), new Sex($sex));
        MysqlConn::getMysqlConnection()->addArtist($artist);
    }
}

?>

<html lang="">
<head>
    <meta charset="utf-8">
    <title>MusRev - Add Artist</title>
</head>
<body>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <h2> Artist information Entry </h2>
    <br>
    Name:
    <label>
        <input type="text" name="name">
        <span class="error">* <?php echo $nameErr; ?></span>
    </label>
    <br>
    BirthDate:
    <label>
        <input type="date" name="birthDate">
    </label>
    <br>
    Sex:
    <label>
        <select name="sex">
            <option value="">Choose a sex</option>
            <?php
            require_once "utils.php";
            require_once "enum/Sex.php";
            echo array2multiChoice(Sex::toArray());
            ?>
        </select>
        <span class="error">* <?php echo $nameErr; ?></span>
    </label>
    <br>
    <input type="submit" value="submit">
</form>

</body>
</html>
