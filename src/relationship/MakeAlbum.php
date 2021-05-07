<?php


class MakeAlbum
{
    private int $artistNumber;
    private int $albumNumber;

    /**
     * MakeAlbum constructor.
     * @param int $artistNumber
     * @param int $albumNumber
     */
    public function __construct(int $artistNumber, int $albumNumber)
    {
        $this->artistNumber = $artistNumber;
        $this->albumNumber = $albumNumber;
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
    public function getAlbumNumber(): int
    {
        return $this->albumNumber;
    }

}