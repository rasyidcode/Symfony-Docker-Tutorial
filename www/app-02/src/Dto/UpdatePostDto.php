<?php

namespace App\Dto;

class UpdatePostDto
{

    private string $title;
    private string $content;

    public static function of(string $title, string $content)
    {
        $post = new UpdatePostDto();
        $post->setTitle($title);
        $post->setContent($content);

        return $post;
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