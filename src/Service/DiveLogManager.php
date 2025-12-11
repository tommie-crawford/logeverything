<?php

namespace App\Service;

use App\Entity\DiveLog;
use App\Message\DiveLogMessage;
use Doctrine\ORM\EntityManagerInterface;

class DiveLogManager
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function createMessage(DiveLog $diveLog, array $images): DiveLogMessage
    {

        if ($diveLog->getDate() > new \DateTimeImmutable('today')) {
            throw new InvalidArgumentException('Dive date cannot be in the future.');
        }

        $dateString = $diveLog->getDate()->format('Y-m-d');

        return new  DiveLogMessage(
            date:     $dateString,
            location: $diveLog->getLocation(),
            notes:    $diveLog->getNotes(),
            images:   $images, // afkomstig uit PhotoUploadService
            maxDepth: $diveLog->getMaxDepth(),
            course: $diveLog->getCourse(),
            duration: $diveLog->getDuration(),

        );
    }
}
