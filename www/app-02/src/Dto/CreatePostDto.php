<?php

namespace App\Dto;

class CreatePostDto
{

    private string $title;
    private string $content;

    public static function of(string $title, string $content): CreatePostDto
    {
        $dto = new CreatePostDto();
        $dto->setTitle($title);
        $dto->setContent($content);

        return $dto;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
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

}