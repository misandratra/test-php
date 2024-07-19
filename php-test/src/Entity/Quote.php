<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;

class Quote
{
    private $id;
    private $siteId;
    private $destinationId;
    private $dateQuoted;

    public function __construct(int $id, int $siteId, int $destinationId, DateTime $dateQuoted)
    {
        $this->id = $id;
        $this->siteId = $siteId;
        $this->destinationId = $destinationId;
        $this->dateQuoted = $dateQuoted;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSiteId(): int
    {
        return $this->siteId;
    }

    public function getDestinationId(): int
    {
        return $this->destinationId;
    }

    public function getDateQuoted(): DateTime
    {
        return $this->dateQuoted;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setSiteId(int $siteId): self
    {
        $this->siteId = $siteId;
        return $this;
    }

    public function setDestinationId(int $destinationId): self
    {
        $this->destinationId = $destinationId;
        return $this;
    }

    public function setDateQuoted(DateTime $dateQuoted): self
    {
        $this->dateQuoted = $dateQuoted;
        return $this;
    }

    public static function renderHtml(self $quote): string
    {
        return '<p>' . htmlspecialchars((string) $quote->getId(), ENT_QUOTES, 'UTF-8') . '</p>';
    }

    public static function renderText(self $quote): string
    {
        return (string) $quote->getId();
    }
}