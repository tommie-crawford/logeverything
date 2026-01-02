<?php

namespace App\Entity;

use App\Repository\FishingLogPhotoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FishingLogPhotoRepository::class)]
class FishingLogPhoto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'photo')]
    private ?FishingLog $fishingLog = null;

    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[ORM\Column(length: 255)]
    private ?string $originalName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFishingLog(): ?FishingLog
    {
        return $this->fishingLog;
    }

    public function setFishingLog(?FishingLog $fishingLog): static
    {
        $this->fishingLog = $fishingLog;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): static
    {
        $this->originalName = $originalName;

        return $this;
    }
}
