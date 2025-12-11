<?php

namespace App\Message;

/**
 * Immutable DTO voor het aanmaken van een DiveLog.
 */
final class DiveLogMessage
{
    /**
     * @param array<int, array{filename: string, originalName: ?string}> $images
     */
    public function __construct(
        private string $date,         // 'Y-m-d'
        private string $location,
        private ?string $notes,
        private array $images,
        private int $maxDepth,
        private ?string $course,
        private float $duration
    ) {}

    public function getDate(): string
    {
        return $this->date;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * @return array<int, array{filename: string, originalName: ?string}>
     */
    public function getImages(): array
    {
        return $this->images;
    }


    public function getCourse(): ?string
    {
        return $this->course;
    }

    public function getDuration(): float
    {
        return $this->duration;
    }

    public function getMaxDepth(): int
    {
        return $this->maxDepth;
    }
}
