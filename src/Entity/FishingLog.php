<?php

namespace App\Entity;

use App\Repository\FishingLogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use App\Enum\FishType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FishingLogRepository::class)]
class FishingLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: FishType::class)]
    private ?FishType $type = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $weight = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $length = null;

    /**
     * @var Collection<int, FishingLogPhoto>
     */
    #[ORM\OneToMany(targetEntity: FishingLogPhoto::class, mappedBy: 'fishingLog')]
    private Collection $photo;

    public function __construct()
    {
        $this->photo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?FishType
    {
        return $this->type;
    }

    public function setType(?FishType $type): self
    {
        $this->type = $type;

        return $this;
}

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getLength(): ?string
    {
        return $this->length;
    }

    public function setLength(?string $length): static
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return Collection<int, FishingLogPhoto>
     */
    public function getPhoto(): Collection
    {
        return $this->photo;
    }

    public function addPhoto(FishingLogPhoto $photo): static
    {
        if (!$this->photo->contains($photo)) {
            $this->photo->add($photo);
            $photo->setFishingLog($this);
        }

        return $this;
    }

    public function removePhoto(FishingLogPhoto $photo): static
    {
        if ($this->photo->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getFishingLog() === $this) {
                $photo->setFishingLog(null);
            }
        }

        return $this;
    }
}
