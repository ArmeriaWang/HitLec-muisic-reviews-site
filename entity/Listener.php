<?php


final class Listener
{
    private int $id;
    private string $name;

    /**
     * User constructor.
     * @param int $listenerId
     * @param string $name
     */
    public function __construct(int $listenerId, string $name)
    {
        $this->id = $listenerId;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

}