DROP VIEW IF EXISTS SongsView;
DROP VIEW IF EXISTS AlbumsView;

DROP TABLE IF EXISTS Comment;
DROP TABLE IF EXISTS Listener;
DROP TABLE IF EXISTS MakeAlbum;
DROP TABLE IF EXISTS MakeSong;
DROP TABLE IF EXISTS Song;
DROP TABLE IF EXISTS Album;
DROP TABLE IF EXISTS Artist;

CREATE TABLE Album
(
    AlbumId     INT AUTO_INCREMENT,
    AlbumName   VARCHAR(256) NOT NULL,
    ReleaseDate DATE,
    PRIMARY KEY (AlbumId)
);

CREATE TABLE Artist
(
    ArtistId   INT AUTO_INCREMENT,
    ArtistName VARCHAR(256)                               NOT NULL,
    BirthDate  Date,
    Sex        ENUM ('male', 'female', 'group', 'others') NOT NULL,
    PRIMARY KEY (ArtistId)
);

CREATE TABLE Song
(
    SongId     INT AUTO_INCREMENT,
    SongName   VARCHAR(256) NOT NULL,
    TimeLength INT          NOT NULL,
    AlbumId    INT          NOT NULL,
    Style      ENUM ('pop', 'jazz', 'rock', 'classic'),
    PRIMARY KEY (SongId),
    FOREIGN KEY (AlbumId) REFERENCES Album (AlbumId)
);

CREATE TABLE Listener
(
    ListenerId   INT AUTO_INCREMENT,
    ListenerName VARCHAR(256) NOT NULL,
    PRIMARY KEY (ListenerId)
);

CREATE TABLE Comment
(
    CommentId  INT AUTO_INCREMENT,
    ListenerId INT          NOT NULL,
    SongId     INT          NOT NULL,
    Content    VARCHAR(256) NOT NULL,
    PRIMARY KEY (CommentId),
    FOREIGN KEY (ListenerId) REFERENCES Listener (ListenerId),
    FOREIGN KEY (SongId) REFERENCES Song (SongId)
);

CREATE TABLE MakeAlbum
(
    ArtistId   INT          NOT NULL,
    AlbumId    INT          NOT NULL,
    ArtistName VARCHAR(256) NOT NULL,
    PRIMARY KEY (ArtistId, AlbumId),
    FOREIGN KEY (ArtistId) REFERENCES Artist (ArtistId),
    FOREIGN KEY (AlbumId) REFERENCES Album (AlbumId)
);

CREATE TABLE MakeSong
(
    ArtistId   INT          NOT NULL,
    SongId     INT          NOT NULL,
    ArtistName VARCHAR(256) NOT NULL,
    PRIMARY KEY (ArtistId, SongId),
    FOREIGN KEY (ArtistId) REFERENCES Artist (ArtistId),
    FOREIGN KEY (SongId) REFERENCES Song (SongId)
);

CREATE VIEW AlbumsView
AS
SELECT AlbumArtists.*,
       IFNULL(Amt, 0) AS SongCnt
FROM (SELECT Album.*,
             GROUP_CONCAT(DISTINCT MA.ArtistId)                        AS ArtistsIds,
             GROUP_CONCAT(DISTINCT MA.ArtistName ORDER BY MA.ArtistId) AS ArtistsNames
      FROM Album
               INNER JOIN MakeAlbum MA ON Album.AlbumId = MA.AlbumId
      GROUP BY AlbumId) AS AlbumArtists
         LEFT JOIN
     (SELECT Album.AlbumId, COUNT(*) AS Amt
      FROM Album
               INNER JOIN Song ON Album.AlbumId = Song.AlbumId
      GROUP BY Album.AlbumId) AS AlbumSong
     ON AlbumArtists.AlbumId = AlbumSong.AlbumId;

CREATE VIEW SongsView
AS
SELECT SongAlbumArtists.*,
       IFNULL(Amt, 0) AS CommentCnt
FROM (SELECT Song.*,
             Album.AlbumName,
             GROUP_CONCAT(DISTINCT MS.ArtistId)                        AS ArtistsIds,
             GROUP_CONCAT(DISTINCT MS.ArtistName ORDER BY MS.ArtistId) AS ArtistsNames
      FROM Song
               INNER JOIN Album ON Song.AlbumId = Album.AlbumId
               INNER JOIN MakeSong MS on Song.SongId = MS.SongId
      GROUP BY Song.SongId) AS SongAlbumArtists
         LEFT JOIN
     (SELECT Song.SongId, COUNT(*) AS Amt
      FROM Song
               INNER JOIN Comment ON Song.SongId = Comment.SongId
      GROUP BY Song.SongId) AS CommentSong
     ON SongAlbumArtists.SongId = CommentSong.SongId;

INSERT INTO Artist (ArtistName, BirthDate, Sex)
VALUES ('Michael Jackson', '1958-08-29', 'male');
INSERT INTO Artist (ArtistName, BirthDate, Sex)
VALUES ('Saara Sofia Aalto', '1987-05-02', 'female');
INSERT INTO Artist (ArtistName, BirthDate, Sex)
VALUES ('KOKIA', '1976-07-22', 'female');
INSERT INTO Artist (ArtistName, BirthDate, Sex)
VALUES ('Asami Abe', '1985-02-27', 'female');
INSERT INTO Artist (ArtistName, BirthDate, Sex)
VALUES ('Wilhelm Kempff', '1895-11-25', 'male');
INSERT INTO Artist (ArtistName, BirthDate, Sex)
VALUES ('Evgeny Kissin', '1971-01-01', 'male');
INSERT INTO Artist (ArtistName, BirthDate, Sex)
VALUES ('Alexander Scriabin', '1872-01-26', 'male');
INSERT INTO Artist (ArtistName, BirthDate, Sex)
VALUES ('Lorin Maazel', '1930-03-06', 'male');
INSERT INTO Artist (ArtistName, BirthDate, Sex)
VALUES ('Arthur Rubinstein', '1887-01-28', 'male');
INSERT INTO Artist (ArtistName, BirthDate, Sex)
VALUES ('Claude Debussy', '1862-08-22', 'male');

BEGIN;
INSERT INTO Album (AlbumName, ReleaseDate)
VALUES ('Happy Birthday to You', '2020-07-30');
INSERT INTO MakeAlbum
VALUES (3, 1, '');
COMMIT;

BEGIN;
INSERT INTO Album (AlbumName, ReleaseDate)
VALUES ('Tokyo Mermaid', '2018-04-25');
INSERT INTO MakeAlbum
VALUES (3, 2, '');
COMMIT;

BEGIN;
INSERT INTO Album (AlbumName, ReleaseDate)
VALUES ('Scream', '2017-09-29');
INSERT INTO MakeAlbum
VALUES (1, 3, '');
COMMIT;

BEGIN;
INSERT INTO Album (AlbumName, ReleaseDate)
VALUES ('Love Never Felt So Good', '2014-05-19');
INSERT INTO MakeAlbum
VALUES (1, 4, '');
COMMIT;

BEGIN;
INSERT INTO Album (AlbumName, ReleaseDate)
VALUES ('Virtual Album Apple', '2000-01-01');
INSERT INTO MakeAlbum
VALUES (1, 5, '');
INSERT INTO MakeAlbum
VALUES (2, 5, '');
COMMIT;

BEGIN;
INSERT INTO Album (AlbumName, ReleaseDate)
VALUES ('Virtual Album Bob', '2000-01-01');
INSERT INTO MakeAlbum
VALUES (5, 6, '');
INSERT INTO MakeAlbum
VALUES (3, 6, '');
COMMIT;

BEGIN;
INSERT INTO Album (AlbumName, ReleaseDate)
VALUES ('Virtual Album Cat', '2000-01-01');
INSERT INTO MakeAlbum
VALUES (5, 7, '');
INSERT INTO MakeAlbum
VALUES (10, 7, '');
INSERT INTO MakeAlbum
VALUES (9, 7, '');
INSERT INTO MakeAlbum
VALUES (8, 7, '');
COMMIT;

BEGIN;
INSERT INTO Album (AlbumName, ReleaseDate)
VALUES ('Virtual Album Dark', '2007-01-01');
INSERT INTO MakeAlbum
VALUES (5, 8, '');
INSERT INTO MakeAlbum
VALUES (1, 8, '');
INSERT INTO MakeAlbum
VALUES (6, 8, '');
INSERT INTO MakeAlbum
VALUES (10, 8, '');
INSERT INTO MakeAlbum
VALUES (9, 8, '');
INSERT INTO MakeAlbum
VALUES (8, 8, '');
COMMIT;

BEGIN;
INSERT INTO Album (AlbumName, ReleaseDate)
VALUES ('Virtual Album Eclipse', '2009-01-01');
INSERT INTO MakeAlbum
VALUES (4, 9, '');
INSERT INTO MakeAlbum
VALUES (3, 9, '');
INSERT INTO MakeAlbum
VALUES (2, 9, '');
INSERT INTO MakeAlbum
VALUES (10, 9, '');
INSERT INTO MakeAlbum
VALUES (8, 9, '');
INSERT INTO MakeAlbum
VALUES (7, 9, '');
COMMIT;

BEGIN;
INSERT INTO Album (AlbumName, ReleaseDate)
VALUES ('Virtual Album Farm', '2019-01-01');
INSERT INTO MakeAlbum
VALUES (1, 10, '');
INSERT INTO MakeAlbum
VALUES (2, 10, '');
INSERT INTO MakeAlbum
VALUES (4, 10, '');
INSERT INTO MakeAlbum
VALUES (6, 10, '');
INSERT INTO MakeAlbum
VALUES (10, 10, '');
INSERT INTO MakeAlbum
VALUES (9, 10, '');
INSERT INTO MakeAlbum
VALUES (7, 10, '');
COMMIT;

UPDATE MakeAlbum
SET ArtistName = (
    SELECT ArtistName
    FROM Artist
    WHERE MakeAlbum.ArtistId = Artist.ArtistId);

# Album 1
BEGIN;
INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('One Virtual Song Alice', 101, 1, 'pop');
INSERT INTO MakeSong
VALUES (3, 1, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('One Virtual Song Banana', 102, 1, 'pop');
INSERT INTO MakeSong
VALUES (3, 2, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('One Virtual Song Camera', 103, 1, 'pop');
INSERT INTO MakeSong
VALUES (3, 3, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('One Virtual Song Dog', 103, 1, 'pop');
INSERT INTO MakeSong
VALUES (3, 4, '');
COMMIT;

# Album 2
BEGIN;
INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Two Virtual Song Alice', 201, 2, 'pop');
INSERT INTO MakeSong
VALUES (3, 5, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Two Virtual Song Banana', 202, 2, 'pop');
INSERT INTO MakeSong
VALUES (3, 6, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Two Virtual Song Camera', 203, 2, 'pop');
INSERT INTO MakeSong
VALUES (3, 7, '');
COMMIT;

#Album 3
BEGIN;
INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Three Virtual Song Alice', 301, 3, 'rock');
INSERT INTO MakeSong
VALUES (1, 8, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Three Virtual Song Banana', 302, 3, 'pop');
INSERT INTO MakeSong
VALUES (1, 9, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Three Virtual Song Camera', 303, 3, 'rock');
INSERT INTO MakeSong
VALUES (1, 10, '');
COMMIT;

#Album 4
BEGIN;
INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Four Virtual Song Dot', 401, 4, 'rock');
INSERT INTO MakeSong
VALUES (1, 11, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Four Virtual Song Epsilon', 402, 4, 'pop');
INSERT INTO MakeSong
VALUES (1, 12, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Four Virtual Song Firework', 403, 4, 'classic');
INSERT INTO MakeSong
VALUES (1, 13, '');
COMMIT;

#Album 5
BEGIN;
INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Five Virtual Song Dot', 501, 5, 'rock');
INSERT INTO MakeSong
VALUES (1, 14, '');
INSERT INTO MakeSong
VALUES (2, 14, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Five Virtual Song Epsilon', 502, 5, 'pop');
INSERT INTO MakeSong
VALUES (1, 15, '');
INSERT INTO MakeSong
VALUES (2, 15, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Five Virtual Song Firework', 503, 5, 'classic');
INSERT INTO MakeSong
VALUES (2, 16, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Five Virtual Song Geek', 504, 5, 'pop');
INSERT INTO MakeSong
VALUES (1, 17, '');
COMMIT;

#Album 6
BEGIN;
INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Six Virtual Song Dot', 601, 6, 'rock');
INSERT INTO MakeSong
VALUES (3, 18, '');
INSERT INTO MakeSong
VALUES (5, 18, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Six Virtual Song Epsilon', 602, 6, 'pop');
INSERT INTO MakeSong
VALUES (5, 19, '');
INSERT INTO MakeSong
VALUES (3, 19, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Six Virtual Song Firework', 603, 6, 'classic');
INSERT INTO MakeSong
VALUES (3, 20, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Six Virtual Song Geek', 604, 6, 'pop');
INSERT INTO MakeSong
VALUES (5, 21, '');
COMMIT;

#Album 7
BEGIN;
INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Seven Virtual Song Destination', 701, 7, 'rock');
INSERT INTO MakeSong
VALUES (8, 22, '');
INSERT INTO MakeSong
VALUES (5, 22, '');
INSERT INTO MakeSong
VALUES (10, 22, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Seven Virtual Song Egg', 702, 7, 'classic');
INSERT INTO MakeSong
VALUES (5, 23, '');
INSERT INTO MakeSong
VALUES (9, 23, '');
COMMIT;

#Album 8
BEGIN;
INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Eight Virtual Song Destination', 801, 8, 'jazz');
INSERT INTO MakeSong
VALUES (8, 24, '');
INSERT INTO MakeSong
VALUES (9, 24, '');
INSERT INTO MakeSong
VALUES (10, 24, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Eight Virtual Song Egg', 802, 8, 'jazz');
INSERT INTO MakeSong
VALUES (5, 25, '');
INSERT INTO MakeSong
VALUES (6, 25, '');
COMMIT;

#Album 9
BEGIN;
INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Nine Virtual Song Arc', 901, 9, 'jazz');
INSERT INTO MakeSong
VALUES (2, 26, '');
INSERT INTO MakeSong
VALUES (3, 26, '');
INSERT INTO MakeSong
VALUES (10, 26, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Nine Virtual Song Boy', 902, 9, 'classic');
INSERT INTO MakeSong
VALUES (7, 27, '');
INSERT INTO MakeSong
VALUES (8, 27, '');
INSERT INTO MakeSong
VALUES (4, 27, '');
INSERT INTO MakeSong
VALUES (10, 27, '');
COMMIT;

#Album 10
BEGIN;
INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Ten Virtual Song Arc', 1001, 10, 'jazz');
INSERT INTO MakeSong
VALUES (2, 28, '');
INSERT INTO MakeSong
VALUES (3, 28, '');
INSERT INTO MakeSong
VALUES (1, 28, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Ten Virtual Song Boy', 1002, 10, 'classic');
INSERT INTO MakeSong
VALUES (5, 29, '');
INSERT INTO MakeSong
VALUES (6, 29, '');
INSERT INTO MakeSong
VALUES (4, 29, '');
INSERT INTO MakeSong
VALUES (7, 29, '');

INSERT INTO Song (SongName, TimeLength, AlbumId, Style)
VALUES ('Ten Virtual Song Harbin', 1003, 10, 'pop');
INSERT INTO MakeSong
VALUES (9, 30, '');
INSERT INTO MakeSong
VALUES (8, 30, '');
INSERT INTO MakeSong
VALUES (1, 30, '');
INSERT INTO MakeSong
VALUES (10, 30, '');
COMMIT;

UPDATE MakeSong
SET ArtistName = (
    SELECT ArtistName
    FROM Artist
    WHERE MakeSong.ArtistId = Artist.ArtistId);

INSERT INTO Listener (ListenerName)
VALUES ('root');
INSERT INTO Listener (ListenerName)
VALUES ('admin');
INSERT INTO Listener (ListenerName)
VALUES ('armeria');

INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (1, 1, 'I love this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (2, 1, 'I like this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (3, 1, 'I dislike this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (1, 2, 'I love this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (2, 2, 'I like this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (3, 2, 'I dislike this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (1, 3, 'I love this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (2, 3, 'I like this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (3, 3, 'I dislike this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (1, 4, 'I love this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (2, 4, 'I like this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (3, 4, 'I dislike this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (1, 5, 'I love this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (2, 5, 'I like this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (3, 5, 'I dislike this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (1, 6, 'I love this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (2, 6, 'I like this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (3, 6, 'I dislike this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (1, 7, 'I love this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (2, 7, 'I like this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (3, 7, 'I dislike this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (1, 8, 'I love this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (2, 8, 'I like this Song!');
INSERT INTO Comment (ListenerId, SongId, Content)
VALUES (3, 8, 'I dislike this Song!');
