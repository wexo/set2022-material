<?php

declare(strict_types=1);

namespace Set2022Blog\Core\Content;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class BlogEntity extends Entity
{
    use EntityIdTrait;
    /** @var string|null */
    protected $name;
    /** @var \DateTime|null */
    protected $date;
    /** @var string|null */
    protected $text;
    /** @var bool */
    protected $active;

    public function setName(?string $value): void
    {
        $this->name = $value;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setDate(?\DateTime $value): void
    {
        $this->date = $value;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setText(?string $value): void
    {
        $this->text = $value;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setActive(bool $value): void
    {
        $this->active = $value;
    }

    public function getActive(): bool
    {
        return $this->active;
    }
}
