<?php

namespace App\Dto;

class PostSummaryDto
{

    private string $id;
    private string $title;

    public static function of(string $id, string $title): PostSummaryDto
    {
        $dto = new PostSummaryDto();
        $dto
            ->setId($id)
            ->setTitle($title);
        
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
     *
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
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
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}