<?php

namespace App\ArgumentResolver;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class QueryParam
{

    private ?string $name;

    private bool $required;

    public function __construct(?string $name = null, bool $required = false)
    {
        $this->name = $name;
        $this->required = $required;
    }


    /**
     * Get the value of name
     *
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param ?string $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of required
     *
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * Set the value of required
     *
     * @param bool $required
     *
     * @return self
     */
    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }

    public function __toString()
    {
        return "QueryParam[name='" . $this->getName() . "', required='" . $this->isRequired() . "']";
    }
}