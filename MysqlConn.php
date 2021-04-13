<?php


require_once "utils.php";
require_once "entity/Song.php";
require_once "entity/Artist.php";
require_once "entity/Album.php";
require_once "enum/Style.php";
require_once "enum/Sex.php";

final class MysqlConn
{
    private string $servername = "127.0.0.1";
    private string $username = "root";
    private string $password = "123456";
    private string $dbname = "db_lab1";
    private mysqli $conn;
    private static ?MysqlConn $instance = null;

    private function __construct()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("connect failed: " . $this->conn->connect_error);
        }
        echo "connection established!<br>";
    }

    private function __clone()
    {
    }

    public function resetTables()
    {
        $conn = $this->conn;
        $conn->query("DROP TABLE Album;");
        $conn->query("DROP TABLE Artist;");
        $conn->query("DROP TABLE Song;");
        $conn->query("DROP TABLE Listener;");
        $conn->query("DROP TABLE Comment;");
        $conn->query("DROP TABLE MakeAlbum;");
        $conn->query("DROP TABLE MakeSong;");

        $conn->query(
            "CREATE TABLE Album (
        AlbumId INT AUTO_INCREMENT,
        Name VARCHAR(256) NOT NULL,
        ReleaseDate DATE,
        Company VARCHAR(256),
        PRIMARY KEY (AlbumId)
    )");

        $conn->query(
            "CREATE TABLE Artist (
        ArtistId INT AUTO_INCREMENT,
        Name VARCHAR(256) NOT NULL,
        BirthDate Date,
        Sex ENUM" . array2string(Sex::toArray()) . " NOT NULL,
        PRIMARY KEY (ArtistId)
    );");

        $conn->query(
            "CREATE TABLE Song (
        SongId INT AUTO_INCREMENT,
        Name VARCHAR(256) NOT NULL,
        TimeLength INT NOT NULL,
        AlbumId INT NOT NULL,
        Style ENUM" . array2string(Style::toArray()) . ",
        PRIMARY KEY (SongId),
        FOREIGN KEY (AlbumId) REFERENCES Album(AlbumId)
    );");

        $conn->query(
            "CREATE TABLE Listener (
        ListenerId INT AUTO_INCREMENT,
        Name VARCHAR(256) NOT NULL,
        PRIMARY KEY (ListenerId)
    );");

        $conn->query(
            "CREATE TABLE Comment (
        CommentId INT AUTO_INCREMENT,
        Name VARCHAR(256) NOT NULL,
        ListenerId INT NOT NULL,
        AlbumId INT NOT NULL,
        Content VARCHAR(256) NOT NULL,
        PRIMARY KEY (CommentId),
        FOREIGN KEY (ListenerId) REFERENCES Listener(ListenerId),
        FOREIGN KEY (AlbumId) REFERENCES Album(AlbumId)
    );");

        $conn->query(
            "CREATE TABLE MakeAlbum (
        ArtistId INT NOT NULL,
        AlbumId INT NOT NULL,
        ArtistName VARCHAR(256) NOT NULL,
        PRIMARY KEY (ArtistId, AlbumId),
        FOREIGN KEY (ArtistId) REFERENCES Artist(ArtistId),
        FOREIGN KEY (AlbumId) REFERENCES Album(AlbumId)
    );");

        $conn->query(
            "CREATE TABLE MakeSong (
        ArtistId INT NOT NULL,
        SongId INT NOT NULL,
        ArtistName VARCHAR(256) NOT NULL,
        PRIMARY KEY (ArtistId, SongId),
        FOREIGN KEY (ArtistId) REFERENCES Artist(ArtistId),
        FOREIGN KEY (SongId) REFERENCES Song(SongId)
    );");
    }

    private function getLatestId(string $tableName): int
    {
        $sql = "SELECT MAX(" . $tableName . "Id) FROM " . $tableName . ";";
        echo $sql . "<br>";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_array($result)[0];
    }

    public static function getMysqlConnection(): MysqlConn
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getMysqli(): mysqli
    {
        return self::getMysqlConnection()->conn;
    }

    private function transactionFailed()
    {
        $errMsg = "Error occurs: " . mysqli_error($this->conn);
        ?>
        <script>alert(<?php echo '"' . $errMsg . '"'; ?>)</script>
        <?php
        $this->conn->rollback();
    }

    public function addArtist(Artist $artist): bool
    {
        $this->conn->begin_transaction();
        $sql = "INSERT INTO Artist (Name, BirthDate, Sex) 
            VALUES ('" . $artist->getName() . "', '"
            . $artist->getBirthDate()->format("Y-m-d") . "', '"
            . $artist->getSex()
            . "');";
        echo $sql . "\n";
        if ($this->conn->query($sql)) {
            ?>
            <script>alert("Artist successfully added!")</script>
            <?php
        } else {
            $this->transactionFailed();
            return false;
        }
        return true;
    }

    public function addAlbum(Album $album, array $artistArr): bool
    {
        $this->conn->begin_transaction();
        $sql = "INSERT INTO Album (Name, ReleaseDate) VALUES ('"
            . $album->getName() . "', '"
            . $album->getReleaseDate()->format("Y-m-d")
            . "');";
        echo $sql . "<br>";
        if ($this->conn->query($sql) == 0) {
            $this->transactionFailed();
            return false;
        }
        $latestId = $this->getLatestId("Album");
        echo "latestId = " . $latestId . "<br>";
        foreach ($artistArr as $artistId) {
            $artistName = mysqli_fetch_array(mysqli_query($this->conn,
                "SELECT Name FROM Artist WHERE ArtistId = " . $artistId . ";"))[0];
            $sql = "INSERT INTO MakeAlbum (ArtistId, AlbumId, ArtistName) VALUES ("
                . $artistId . ", "
                . $latestId . ", "
                . $artistName
                . ');';
            echo $sql . "<br>";
            if ($this->conn->query($sql) == 0) {
                $this->transactionFailed();
                return false;
            }
        }
        $this->conn->commit();
        ?>
        <script>alert("Album successfully added!")</script>
        <?php
        return true;
    }

    public function addSong(Song $song, array $artistArr): bool
    {
        $this->conn->begin_transaction();
        $sql = "INSERT INTO Song (Name, TimeLength, AlbumId, Style) VALUES ('"
            . $song->getName() . "', '"
            . $song->getTimeLength() . "', '"
            . $song->getAlbumId() . "', '"
            . $song->getStyle()
            . "');";
        echo $sql . "<br>";
        if ($this->conn->query($sql) == 0) {
            $this->transactionFailed();
            return false;
        }
        $latestId = $this->getLatestId("Song");
        echo "latestId = " . $latestId . "<br>";
        foreach ($artistArr as $artistId) {
            $artistName = mysqli_fetch_array(mysqli_query($this->conn,
                "SELECT Name FROM Artist WHERE ArtistId = " . $artistId . ";"))[0];
            $sql = "INSERT INTO MakeSong (ArtistId, SongId, ArtistName) VALUES ("
                . $artistId . ", "
                . $latestId . ", "
                . $artistName
                . ');';
            echo $sql . "<br>";
            if ($this->conn->query($sql) == 0) {
                $this->transactionFailed();
                return false;
            }
        }
        $this->conn->commit();
        ?>
        <script>alert("Album successfully added!")</script>
        <?php
        return true;
    }

    public function addListener(Listener $listener): bool
    {
        $this->conn->begin_transaction();
        $sql = "INSERT INTO Listener (Name) 
            VALUES ('" . $listener->getName()
            . "');";
        echo $sql . "\n";
        if ($this->conn->query($sql)) {
            ?>
            <script>alert("Listener successfully added!")</script>
            <?php
        } else {
            $this->transactionFailed();
            return false;
        }
        return true;
    }

    public function getSongById(int $songId)
    {
        $result = mysqli_query($this->conn,
            "SELECT * FROM SongsView WHERE SongId = " . $songId . ";");
        if ($result == false) {
            return false;
        }
        $songInfo = mysqli_fetch_array($result);
        $song = new Song($songId, $songInfo["Name"], $songInfo["TimeLength"],
            $songInfo["AlbumId"], new Style($songInfo["Style"]));
        $albumName = $songInfo["AlbumName"];
        $result = mysqli_query($this->conn,
            "SELECT * FROM MakeSong WHERE SongId = " . $songId . ";");
        $artistsNames = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($artistsNames, $row["ArtistName"]);
        }
        return array($song, $albumName, $artistsNames);
    }

}