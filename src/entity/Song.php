<?php


require_once "../enum/Style.php";
final class Song
{
    private int $songId;
    private string $songName;
    private int $timeLength;
    private int $albumId;
    private Style $style;
    private ?array $artists;

    /**
     * Song constructor.
     * @param int $songId
     * @param string $songName
     * @param int $timeLength
     * @param int $albumNumber
     * @param Style $style
     * @param array|null $artists
     */

    public function __construct(int $songId, string $songName, int $timeLength, int $albumNumber, Style $style,
                                array $artists = null)
    {
        $this->songId = $songId;
        $this->songName = $songName;
        $this->timeLength = $timeLength;
        $this->albumId = $albumNumber;
        $this->style = $style;
        $this->artists = $artists;
    }

    /**
     * @return int
     */
    public function getSongId(): int
    {
        return $this->songId;
    }

    /**
     * @return string
     */
    public function getSongName(): string
    {
        return $this->songName;
    }

    /**
     * @return int
     */
    public function getTimeLength(): int
    {
        return $this->timeLength;
    }

    /**
     * @return int
     */
    public function getAlbumId(): int
    {
        return $this->albumId;
    }

    /**
     * @return Style
     */
    public function getStyle(): Style
    {
        return $this->style;
    }

    /**
     * @return array|null
     */
    public function getArtists(): ?array
    {
        return $this->artists;
    }


}