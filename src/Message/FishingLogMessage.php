<?php

namespace App\Message;

use App\Enum\FishType;

/**
 * Immutable DTO voor het aanmaken van een FishingLog.
 */
final class FishingLogMessage
{
    /**
     * @param array<int, array{filename: string, originalName: ?string}> $images
     */
    public function __construct(
        private string $date,
        private FishType $type,
        private ?string $weight,
        private ?string $length,
        private array $images,
    ) {}

    public function getDate(): string
    {
        return $this->date;
    }
    
    // Generate getters for all properties
    public function getType(): FishType
    {
        return $this->type;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }
    
    public function getLength(): ?string
    {
        return $this->length;
    }

    public function getImages(): array
    {
        return $this->images;
    }   
}
