<?php


final class Album
{
    private int $albumId;
    private string $albumName;
    private DateTime $releaseDate;
    private ?array $artistsIds;
    private ?array $songsIds;

    /**
     * Album constructor.
     * @param int $number
     * @param string $albumName
     * @param DateTime $releaseDate
     * @param array|null $artistsIds
     * @param array|null $songsIds
     */
    public function __construct(int $number, string $albumName, DateTime $releaseDate,
                                array $artistsIds = null, array $songsIds = null)
    {
        $this->albumId = $number;
        $this->albumName = $albumName;
        $this->releaseDate = $releaseDate;
        $this->artistsIds = $artistsIds;
        $this->songsIds = $songsIds;
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
    public function getArtistsIds(): ?array
    {
        return $this->artistsIds;
    }

    /**
     * @return array|null
     */
    public function getSongsIds(): ?array
    {
        return $this->songsIds;
    }

}