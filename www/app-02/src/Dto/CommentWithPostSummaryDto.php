<?php

namespace App\Dto;

class CommentWithPostSummaryDto
{

    private string $id;

    private string $content;

    private PostSummaryDto $post;

    public static function of(string $id, string $content, PostSummaryDto $post): self
    {
        $dto = new CommentWithPostSummaryDto();
        $dto->setId($id);
        $dto->setContent($content);
        $dto->setPost($post);

        return $dto;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return PostSummaryDto
     */
    public function getPost(): PostSummaryDto
    {
        return $this->post;
    }

    /**
     * @param PostSummaryDto $post
     */
    public function setPost(PostSummaryDto $post): void
    {
        $this->post = $post;
    }



}