<?php


final class Album
{
    private int $number;
    private string $name;
    private DateTime $releaseDate;
    private string $company;

    /**
     * Album constructor.
     * @param int $albumId
     * @param string $name
     * @param DateTime $releaseDate
     * @param string $company
     * @throws Exception
     */
    public function __construct(int $albumId, string $name, DateTime $releaseDate, string $company)
    {
        if ($albumId <= 0) {
            throw new UnexpectedValueException();
        }
        $this->number = $albumId;
        $this->name = $name;
        $this->releaseDate = $releaseDate;
        $this->company = $company;
    }

    /**
     * @return int
     */
    public function getNumber(): int
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

    /**
     * @return string
     */
    public function getCompany(): string
    {
        return $this->company;
    }

}