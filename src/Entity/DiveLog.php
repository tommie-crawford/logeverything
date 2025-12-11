<?php

namespace App\Entity;

use App\Repository\DiveLogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiveLogRepository::class)]
class DiveLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes = null;

    #[ORM\Column]
    private ?int $maxDepth = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $course = null;

    #[ORM\Column]
    private ?float $duration = null;

    /**
     * @var Collection<int, DiveLogPhoto>
     */
    #[ORM\OneToMany(targetEntity: DiveLogPhoto::class, mappedBy: 'divelog', cascade: ['persist', 'remove'])]
    private Collection $diveLogPhotos;

    public function __construct()
    {
        $this->diveLogPhotos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @return Collection<int, DiveLogPhoto>
     */
    public function getDiveLogPhotos(): Collection
    {
        return $this->diveLogPhotos;
    }

    public function addDiveLogPhoto(DiveLogPhoto $diveLogPhoto): static
    {
        if (!$this->diveLogPhotos->contains($diveLogPhoto)) {
            $this->diveLogPhotos->add($diveLogPhoto);
            $diveLogPhoto->setDivelog($this);
        }

        return $this;
    }

    public function removeDiveLogPhoto(DiveLogPhoto $diveLogPhoto): static
    {
        if ($this->diveLogPhotos->removeElement($diveLogPhoto)) {
            // set the owning side to null (unless already changed)
            if ($diveLogPhoto->getDivelog() === $this) {
                $diveLogPhoto->setDivelog(null);
            }
        }

        return $this;
    }

    public function getMaxDepth(): ?int
    {
        return $this->maxDepth;
    }

    public function setMaxDepth(?int $maxDepth): void
    {
        $this->maxDepth = $maxDepth;
    }

    public function getCourse(): ?string
    {
        return $this->course;
    }

    public function setCourse(?string $course): void
    {
        $this->course = $course;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(?float $duration): void
    {
        $this->duration = $duration;
    }
}
