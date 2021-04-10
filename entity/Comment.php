<?php


final class Comment
{
    private int $id;
    private int $listenerId;
    private int $songId;
    private string $content;

    /**
     * SongComment constructor.
     * @param int $id
     * @param int $listenerId
     * @param int $songId
     * @param string $content
     */
    public function __construct(int $id, int $listenerId, int $songId, string $content)
    {
        $this->id = $id;
        $this->listenerId = $listenerId;
        $this->songId = $songId;
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getListenerId(): int
    {
        return $this->listenerId;
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
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param Comment $comment
     * @return boolean
     */
    public function equals(Comment $comment): bool
    {
        return $this->id == $comment->id;
    }

}