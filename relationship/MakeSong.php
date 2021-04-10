<?php


final class MakeSong
{
    private int $artistNumber;
    private int $songNumber;

    /**
     * MakeSong constructor.
     * @param int $artistNumber
     * @param int $songNumber
     */
    public function __construct(int $artistNumber, int $songNumber)
    {
        $this->artistNumber = $artistNumber;
        $this->songNumber = $songNumber;
    }

    /**
     * @return int
     */
    public function getArtistNumber(): int
    {
        return $this->artistNumber;
    }

    /**
     * @return int
     */
    public function getSongNumber(): int
    {
        return $this->songNumber;
    }

}