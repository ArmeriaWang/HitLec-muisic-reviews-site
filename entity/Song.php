<?php


require_once "enum/Style.php";
final class Song
{
    private int $number;
    private string $name;
    private int $timeLength;
    private int $albumId;
    private Style $style;

    /**
     * Song constructor.
     * @param int $number
     * @param string $name
     * @param int $timeLength
     * @param int $albumNumber
     * @param Style $style
     */

    public function __construct(int $number, string $name, int $timeLength, int $albumNumber, Style $style)
    {
        $this->number = $number;
        $this->name = $name;
        $this->timeLength = $timeLength;
        $this->albumId = $albumNumber;
        $this->style = $style;
    }

    /**
     * @return int
     */
    public function getSongId(): int
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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

}