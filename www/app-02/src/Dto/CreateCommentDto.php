<?php

namespace App\Dto;

class CreateCommentDto
{

    private string $content;

    public static function of(string $content): self
    {
        $dto = new CreateCommentDto();
        $dto->setContent($content);
        return $dto;
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