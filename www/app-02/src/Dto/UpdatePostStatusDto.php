<?php

namespace App\Dto;

use App\Entity\Status;

class UpdatePostStatusDto
{

    private Status $status;

    public static function of(Status $status): self
    {
        $updatePostStatusDto = new UpdatePostStatusDto();
        $updatePostStatusDto->setStatus($status);

        return $updatePostStatusDto;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @param Status $status
     */
    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

}