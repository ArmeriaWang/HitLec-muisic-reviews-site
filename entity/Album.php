<?php


final class Album
{
    private int $number;
    private string $name;
    private DateTime $releaseDate;
//    private string $company;

    /**
     * Album constructor.
     * @param int $albumId
     * @param string $name
     * @param DateTime $releaseDate
     * @throws Exception
     */
    public function __construct(int $albumId, string $name, DateTime $releaseDate)
    {
//        if ($albumId <= 0) {
//            throw new UnexpectedValueException();
//        }
        $this->number = $albumId;
        $this->name = $name;
        $this->releaseDate = $releaseDate;
    }

    /**
     * @return int
     */
    public function getAlbumId(): int
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
     * @return DateTime
     */
    public function getReleaseDate(): DateTime
    {
        return $this->releaseDate;
    }

}