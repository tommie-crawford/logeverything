<?php

namespace App\Service;

use App\Entity\FishingLog;
use App\Message\FishingLogMessage;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

class FishingLogManager
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function createMessage(FishingLog $fishingLog, array $images): FishingLogMessage
    {
        if ($fishingLog->getDate() > new \DateTimeImmutable('today')) {
            throw new InvalidArgumentException('Fishing date cannot be in the future.');
        }

        $dateString = $fishingLog->getDate()->format('Y-m-d');

        return new FishingLogMessage(
            date: $dateString,
            type: $fishingLog->getType(),
            weight: $fishingLog->getWeight(),
            length: $fishingLog->getLength(),
            images: $images,
        );
    }
}
