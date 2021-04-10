<?php


final class Artist
{

    private int $number;
    private string $name;
    private string $artName;
    private Datetime $birthDate;
    private Sex $sex;

    /**
     * Artist constructor.
     * @param int $number
     * @param string $name
     * @param Datetime $birthDate
     * @param Sex $sex
     */
    public function __construct(int $number, string $name, Datetime $birthDate, Sex $sex)
    {
        $this->number = $number;
        $this->name = $name;
        $this->birthDate = $birthDate;
        $this->sex = $sex;
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

}