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
//        echo "connection established!<br>";
    }

    private function __clone()
    {
    }

    public function resetTables()
    {
        $conn = $this->conn;
        $conn->query("DROP TABLE MakeSong;");
        $conn->query("DROP TABLE MakeAlbum;");
        $conn->query("DROP TABLE Comment;");
        $conn->query("DROP TABLE Listener;");
        $conn->query("DROP TABLE Song;");
        $conn->query("DROP TABLE Album;");
        $conn->query("DROP TABLE Artist;");

        $conn->query(
            "CREATE TABLE Album (
            AlbumId INT AUTO_INCREMENT,
            AlbumName VARCHAR(256) NOT NULL,
            ReleaseDate DATE,
            PRIMARY KEY (AlbumId)
        )");

        $conn->query(
            "CREATE TABLE Artist (
            ArtistId INT AUTO_INCREMENT,
            ArtistName VARCHAR(256) NOT NULL,
            BirthDate Date,
            Sex ENUM" . array2string4insertion(Sex::toArray()) . " NOT NULL,
            PRIMARY KEY (ArtistId)
        );");

        $conn->query(
            "CREATE TABLE Song (
            SongId INT AUTO_INCREMENT,
            SongName VARCHAR(256) NOT NULL,
            TimeLength INT NOT NULL,
            AlbumId INT NOT NULL,
            Style ENUM" . array2string4insertion(Style::toArray()) . ",
            PRIMARY KEY (SongId),
            FOREIGN KEY (AlbumId) REFERENCES Album(AlbumId)
        );");

        $conn->query(
            "CREATE TABLE Listener (
            ListenerId INT AUTO_INCREMENT,
            ListenerName VARCHAR(256) NOT NULL,
            PRIMARY KEY (ListenerId)
        );");

        $conn->query(
            "CREATE TABLE Comment (
            CommentId INT AUTO_INCREMENT,
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

    /**
     * Transaction failed. Alerts error message and rollback.
     *
     * @param string $errMsg Error message. If not given, alerts MySQL error
     */
    private function transactionFailed(string $errMsg = "")
    {
        if (strlen($errMsg) == 0) {
            $errMsg = "Error occurs: " . mysqli_error($this->conn);
        }
        ?>
        <script>alert(<?php echo '"' . $errMsg . '"'; ?>)</script>
        <?php
        $this->conn->rollback();
    }

    /**
     * 向数据库中添加一条新的艺术家 Artist 信息
     *
     * @param Artist $artist Artist实例，子段 Name、BirthDate、Sex 应为有效值
     * @return bool 是否添加成功
     */
    public function addArtist(Artist $artist): bool
    {
        $this->conn->begin_transaction();
        $sql = "INSERT INTO Artist (ArtistName, BirthDate, Sex) 
            VALUES ('" . $artist->getName() . "', '"
            . $artist->getBirthDate()->format("Y-m-d") . "', '"
            . $artist->getSex()
            . "');";
        echo $sql . "<br>";
        if ($this->conn->query($sql) == false) {
            $this->transactionFailed();
            return false;
        }
        $this->conn->commit();
        echo '<script>alert("Artist successfully added!")</script>';
        return true;
    }

    public function addAlbum(Album $album): bool
    {
        $this->conn->begin_transaction();
        $sql = "INSERT INTO Album (AlbumName, ReleaseDate) VALUES ('"
            . $album->getAlbumName() . "', '"
            . $album->getReleaseDate()->format("Y-m-d")
            . "');";
        echo $sql . "<br>";
        if ($this->conn->query($sql) == 0) {
            $this->transactionFailed();
            return false;
        }
        $latestId = $this->getLatestId("Album");
        echo "latestId = " . $latestId . "<br>";
        $artists = $album->getArtists();
        foreach ($artists as $artist) {
            $artist = $this->getArtistByArtistId($artist->getArtistId());
            if ($artist == false) {
                $this->transactionFailed();
                return false;
            }
            $sql = "INSERT INTO MakeAlbum (ArtistId, AlbumId, ArtistName) VALUES ("
                . $artist->getArtistId() . ", "
                . $latestId . ", '"
                . $artist->getName() . "'"
                . ");";
            echo $sql . "<br>";
            if ($this->conn->query($sql) == false) {
                $this->transactionFailed();
                return false;
            }
        }
        $this->conn->commit();
        echo '<script>alert("Album successfully added!")</script>';
        return true;
    }

    public function addSong(Song $song): bool
    {
        $this->conn->begin_transaction();
        $sql = "INSERT INTO Song (SongName, TimeLength, AlbumId, Style) VALUES ('"
            . $song->getSongName() . "', '"
            . $song->getTimeLength() . "', '"
            . $song->getAlbumId() . "', '"
            . $song->getStyle() . "'"
            . ");";
        echo $sql . "<br>";
        if ($this->conn->query($sql) == false) {
            $this->transactionFailed();
            return false;
        }
        $latestId = $this->getLatestId("Song");
        echo "latestId = " . $latestId . "<br>";
        $artists = $song->getArtists();
        foreach ($artists as $artist) {
            $artist = $this->getArtistByArtistId($artist->getArtistId());
            if ($artist == false) {
                $this->transactionFailed();
                return false;
            }
            $sql = "INSERT INTO MakeSong (ArtistId, SongId, ArtistName) VALUES ("
                . $artist->getArtistId() . ", "
                . $latestId . ", '"
                . $artist->getName() . "'"
                . ");";
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
        $sql = "INSERT INTO Listener (ListenerName) 
            VALUES ('" . $listener->getName()
            . "');";
        echo $sql . "<br>";
        if ($this->conn->query($sql) == false) {
            $this->transactionFailed();
            return false;
        }
        $this->conn->commit();
        $listenerId = $this->getLatestId("Listener");
        $successAlert = "Listener (" . $listenerId . ", " . $listener->getName() . ") successfully added!";
        echo '<script>alert("' . $successAlert . '")</script>';
        return true;
    }

    public function addComment(Comment $comment): bool
    {
        $this->conn->begin_transaction();
        $sql = "INSERT INTO Comment (ListenerId, SongId, Content) 
            VALUES (" . $comment->getListenerId() . ", "
            . $comment->getSongId() . ", '"
            . $comment->getContent() . "'"
            . ");";
        echo $sql . "<br>";
        if ($this->conn->query($sql) == false) {
            $this->transactionFailed();
            return false;
        }
        $this->conn->commit();
        echo '<script>alert("Comment successfully added!")</script>';
        return true;
    }

    /**
     * @param int $songId
     * @return array|false array[0] is a instance of Song, array[1] is its album name
     */
    public function getSongById(int $songId)
    {
        $result = mysqli_query($this->conn,
            "SELECT * FROM SongsView WHERE SongId = " . $songId . ";");
        if ($result == false) {
            return false;
        }
        $songInfo = mysqli_fetch_array($result);

        $result = mysqli_query($this->conn,
            "SELECT * FROM MakeSong WHERE SongId = " . $songId . ";");
        $artistsNames = array();
        $artistsIds = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($artistsNames, $row["ArtistName"]);
            array_push($artistsIds, $row["ArtistId"]);
        }
        $albumName = $songInfo["AlbumName"];
        $song = new Song($songId, $songInfo["SongName"], $songInfo["TimeLength"],
            $songInfo["AlbumId"], new Style($songInfo["Style"]), $artistsIds);
        return array($song, $albumName);
    }

    /**
     * @param int $artistId
     * @return false|Artist
     */
    public function getArtistByArtistId(int $artistId)
    {
        $result = mysqli_query($this->conn,
            "SELECT * FROM Artist WHERE ArtistId = " . $artistId . ";");
        if ($result == false) {
            return false;
        }
        $row = mysqli_fetch_array($result);
        return new Artist($artistId, $row['Name'],
            DateTime::createFromFormat("Y-m-d", $row['BirthDate']),
            new Sex($row['Sex']));
    }

    public function getSongArtistsBySongId(int $songId)
    {
        $result = mysqli_query($this->conn,
            "SELECT * FROM MakeSong WHERE SongId = " . $songId . ";");
        if ($result == false) {
            return false;
        }
        $artistsNames = array();
        $artistsIds = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($artistsNames, $row["ArtistName"]);
            array_push($artistsIds, $row["ArtistId"]);
        }
        return array($artistsIds, $artistsNames);
    }

    public function getListenerById(int $listenerId)
    {
        $result = mysqli_query($this->conn,
            "SELECT * FROM Listener WHERE ListenerId = " . $listenerId . ";");
        if ($result == false) {
            return false;
        }
        $listenerInfo = mysqli_fetch_array($result);
        return new Listener($listenerId, $listenerInfo["ListenerName"]);
    }

    public function getCommentsBySongId(int $songId)
    {
        $result = mysqli_query($this->conn,
            "SELECT * FROM Comment WHERE SongId = " . $songId . ";");
        if ($result == false) {
            return false;
        }
        $comments = array();
        while ($row = mysqli_fetch_array($result)) {
            array_push($comments,
                new Comment($row["CommentId"], $row["ListenerId"], $row["SongId"], $row["Content"]));
        }
        return $comments;
    }

    /**
     * Delete a song and all its comments
     *
     * @param int $songId
     * @param bool $newTransaction 是否需要使用事务（默认为true，仅当调用者已经在使用事务时才应传入false）
     * @return bool success or not
     */
    public function deleteSongById(int $songId, bool $newTransaction = true): bool
    {
        if ($newTransaction) {
            $this->conn->begin_transaction();
        }
        $sql = "DELETE FROM Comment WHERE SongId = " . $songId . ";";
        $result = mysqli_query($this->conn, $sql);
        if ($result == false) {
            if ($newTransaction) {
                $this->transactionFailed();
            }
            return false;
        }
        $sql = "DELETE FROM MakeSong WHERE SongId = " . $songId . ";";
        $result = mysqli_query($this->conn, $sql);
        if ($result == false) {
            if ($newTransaction) {
                $this->transactionFailed();
            }
            return false;
        }
        $sql = "DELETE FROM Song WHERE SongId = " . $songId . ";";
        $result = mysqli_query($this->conn, $sql);
        if ($result == false) {
            if ($newTransaction) {
                $this->transactionFailed();
            }
            return false;
        }
        if ($newTransaction) {
            $this->conn->commit();
            $successAlert = "Successfully deleted Song " . $songId;
            echo '<script>alert("' . $successAlert . '")</script>';
        }
        return true;
    }

    /**
     * Delete an album and all its songs
     *
     * @param int $albumId
     * @return bool success or not
     */
    public function deleteAlbumById(int $albumId): bool
    {
        $this->conn->begin_transaction();
        $sql = "SELECT * FROM Album WHERE AlbumId = " . $albumId . ";";
        $result = mysqli_query($this->conn, $sql);
        if ($result == false || mysqli_fetch_array($result) == false) {
            $this->transactionFailed("No Album with AlbumId = " . $albumId);
            return false;
        }
        $sql = "SELECT SongId FROM Song WHERE AlbumId = " . $albumId . ";";
        $result = mysqli_query($this->conn, $sql);
        if ($result == false) {
            $this->transactionFailed();
            return false;
        }
        while ($row = mysqli_fetch_array($result)) {
            $songId = $row[0];
            if ($this->deleteSongById($songId, false) == false) {
                $this->transactionFailed();
                return false;
            }
        }
        $sql = "DELETE FROM MakeAlbum WHERE AlbumId = " . $albumId . ";";
        $result = mysqli_query($this->conn, $sql);
        if ($result == false) {
            $this->transactionFailed();
            return false;
        }
        $sql = "DELETE FROM Album WHERE AlbumId = " . $albumId . ";";
        $result = mysqli_query($this->conn, $sql);
        if ($result == false) {
            $this->transactionFailed();
            return false;
        }
        $this->conn->commit();
        $successAlert = "Successfully deleted Album " . $albumId;
        echo '<script>alert("' . $successAlert . '")</script>';
        return true;
    }

}