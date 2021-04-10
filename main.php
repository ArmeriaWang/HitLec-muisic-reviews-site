<?php

require_once("vendor/autoload.php");
require_once 'entity/enum/Sex.php';
require_once 'entity/enum/Style.php';

$servername = "127.0.0.1";
$username = "root";
$password = "123456";
$dbname = "db_lab1";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("connect failed: " . $conn->connect_error);
}
echo "connection established!\n";

function array2string($a): string
{
    $ret = "";
    foreach ($a as $x) {
        $ret = $ret . "'" . $x . "', ";
    }
    return "(" . substr($ret, 0, -2) . ")";
}

function buildTables(mysqli $conn)
{
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

function addAlbum()
{

}

buildTables($conn);

addAlbum();
