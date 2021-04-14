<?php


final class Comment
{
    private int $commentId;
    private int $listenerId;
    private int $songId;
    private string $content;

    /**
     * SongComment constructor.
     * @param int $commentId
     * @param int $listenerId
     * @param int $songId
     * @param string $content
     */
    public function __construct(int $commentId, int $listenerId, int $songId, string $content)
    {
        $this->commentId = $commentId;
        $this->listenerId = $listenerId;
        $this->songId = $songId;
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getCommentId(): int
    {
        return $this->commentId;
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
        return $this->commentId == $comment->commentId;
    }

}