<?php


require_once "../enum/Sex.php";
final class Artist
{

    private int $artistId;
    private string $name;
    private ?Datetime $birthDate;
    private ?Sex $sex;

    /**
     * Artist constructor.
     * @param int $artistId
     * @param string $name
     * @param ?Datetime $birthDate
     * @param ?Sex $sex
     */
    public function __construct(int $artistId, string $name = "", Datetime $birthDate = null, Sex $sex = null)
    {
        if ($artistId < 0) {
            throw new UnexpectedValueException();
        }
        $this->artistId = $artistId;
        $this->name = $name;
        $this->birthDate = $birthDate;
        $this->sex = $sex;
    }

    /**
     * @return int
     */
    public function getArtistId(): int
    {
        return $this->artistId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Datetime
     */
    public function getBirthDate(): Datetime
    {
        return $this->birthDate;
    }

    /**
     * @return Sex
     */
    public function getSex(): Sex
    {
        return $this->sex;
    }

    /**
     * @param int $artistId
     */
    public function setArtistId(int $artistId): void
    {
        $this->artistId = $artistId;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param Datetime $birthDate
     */
    public function setBirthDate(Datetime $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @param Sex $sex
     */
    public function setSex(Sex $sex): void
    {
        $this->sex = $sex;
    }

}