<?php


final class Album
{
    private int $albumId;
    private string $albumName;
    private DateTime $releaseDate;
    private ?array $songs;
    private ?array $artists;

    /**
     * Album constructor.
     * @param int $number
     * @param string $albumName
     * @param DateTime $releaseDate
     * @param array|null $artists
     * @param array|null $songs
     */
    public function __construct(int $number, string $albumName, DateTime $releaseDate,
                                array $artists = null, array $songs = null)
    {
        $this->albumId = $number;
        $this->albumName = $albumName;
        $this->releaseDate = $releaseDate;
        $this->artists = $artists;
        $this->songs = $songs;
    }


    /**
     * @return int
     */
    public function getAlbumId(): int
    {
        return $this->albumId;
    }

    /**
     * @return string
     */
    public function getAlbumName(): string
    {
        return $this->albumName;
    }

    /**
     * @return DateTime
     */
    public function getReleaseDate(): DateTime
    {
        return $this->releaseDate;
    }

    /**
     * @return array|null
     */
    public function getArtists(): ?array
    {
        return $this->artists;
    }

    /**
     * @return array|null
     */
    public function getSongs(): ?array
    {
        return $this->songs;
    }

}