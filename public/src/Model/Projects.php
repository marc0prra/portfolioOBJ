<?php

namespace Perei\PortfolioObj\Model;

class Projects
{
    private ?int $id;
    private string $title;
    private string $description;
    private string $image;
    private string $date;
    public function __construct(
        string $title,
        string $description,
        \DateTime|string $date,
        string $image,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->date = $date instanceof \DateTime ? $date : new \DateTime($date);
        $this->image = $image;
    }
    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getImage(): string
    {
        return $this->image;
    }
    public function getDate(): string
    {
        return $this->date;
    }
    // Setters
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    public function setDate(\DateTime|string $date): void
    {
        $this->date = $date instanceof \DateTime ? $date : new \DateTime($date);
    }
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

}
