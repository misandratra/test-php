<?php

declare(strict_types=1);

namespace App\Entity;

class Destination
{
    private $id;
    private $countryName;
    private $conjunction;
    private $name;
    private $computerName;

    public function __construct(int $id, string $countryName, string $conjunction, string $computerName)
    {
        $this->id = $id;
        $this->countryName = $countryName;
        $this->conjunction = $conjunction;
        $this->computerName = $computerName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCountryName(): string
    {
        return $this->countryName;
    }

    public function getConjunction(): string
    {
        return $this->conjunction;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getComputerName(): string
    {
        return $this->computerName;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setCountryName(string $countryName): self
    {
        $this->countryName = $countryName;
        return $this;
    }

    public function setConjunction(string $conjunction): self
    {
        $this->conjunction = $conjunction;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setComputerName(string $computerName): self
    {
        $this->computerName = $computerName;
        return $this;
    }
}