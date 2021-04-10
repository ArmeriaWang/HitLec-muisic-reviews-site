<?php


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
        echo "connection established!\n";
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
        PRIMARY KEY (ArtistId, AlbumId),
        FOREIGN KEY (ArtistId) REFERENCES Artist(ArtistId),
        FOREIGN KEY (AlbumId) REFERENCES Album(AlbumId)
    );");

        $conn->query(
            "CREATE TABLE MakeSong (
        ArtistId INT NOT NULL,
        SongId INT NOT NULL,
        PRIMARY KEY (ArtistId, SongId),
        FOREIGN KEY (ArtistId) REFERENCES Artist(ArtistId),
        FOREIGN KEY (SongId) REFERENCES Song(SongId)
    );");
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
        ?>
        <script>alert("Error occurs!"</script>
        <?php
        $this->conn->rollback();
    }

    public function addArtist(Artist $artist)
    {
        $sql = "INSERT INTO Artist (Name, BirthDate, Sex) 
            VALUES ('" . $artist->getName() .
            "', '" . $artist->getBirthDate()->format("Y-m-d") .
            "', '" . $artist->getSex() .
            "');";
        echo $sql . "\n";
        if ($this->conn->query($sql)) {
            ?>
            <script>alert("Artist successfully added!")</script>
            <?php
        } else {
            ?>
            <script>alert("Error occurs!"</script>
            <?php
        }
    }

    public function addAlbum(Album $album, array $artists)
    {
        $this->conn->begin_transaction();
        $sql = "INSERT INTO Album (Name, ReleaseDate) 
            VALUES ('" . $album->getName() .
            "', '" . $album->getReleaseDate()->format("Y-m-d") .
            "');";
        echo $sql . "\n";
        if ($this->conn->query($sql) == 0) {
            $this->transactionFailed();
            return;
        }
        foreach ($artists as $artistId) {
            $sql = "INSERT INTO MakeAlbum (ArtistId, AlbumId) 
                VALUES ('" . $artistId . "', " . $album->getAlbumId() . ');';
            if ($this->conn->query($sql) == 0) {
                $this->transactionFailed();
                return;
            }
        }
        $this->conn->commit();
    }

    public function addSong(Song $song, array $artists)
    {
        $this->conn->begin_transaction();
        $sql = "INSERT INTO Song (Name, AlbumId, Style, TimeLength) 
            VALUES ('" . $song->getName() .
            "', '" . $song->getAlbumId() .
            "', '" . $song->getStyle() .
            "', '" . $song->getTimeLength() .
            "');";
        if ($this->conn->query($sql) == 0) {
            $this->transactionFailed();
            return;
        }
        foreach ($artists as $artistId) {
            $sql = "INSERT INTO MakeSong (ArtistId, SongId) 
                VALUES ('" . $artistId . "', " . $song->getAlbumId() .
                ");";
            if ($this->conn->query($sql) == 0) {
                $this->transactionFailed();
                return;
            }
        }
        $this->conn->commit();
    }

}