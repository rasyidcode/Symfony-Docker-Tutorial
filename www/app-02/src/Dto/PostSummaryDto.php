<?php

namespace App\Dto;

use App\Entity\Status;

class PostSummaryDto
{

    private string $id;
    private string $title;
    private string $content;
    private ?Status $status;

    public static function of(string $id, string $title, string $content, ?Status $status): PostSummaryDto
    {
        $dto = new PostSummaryDto();
        $dto->setId($id);
        $dto->setTitle($title);
        $dto->setContent($content);
        $dto->setStatus($status);

        return $dto;
    }


    /**
     * Get the value of id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param string $id
     */
    public function setId(string $id):void
    {
        $this->id = $id;
    }

    /**
     * Get the value of title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param string $title
     *
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
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
     * @return ?Status
     */
    public function getStatus(): ?Status
    {
        return $this->status;
    }

    /**
     * @param ?Status $status
     */
    public function setStatus(?Status $status): void
    {
        $this->status = $status;
    }
}