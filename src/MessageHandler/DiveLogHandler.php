<?php

namespace App\MessageHandler;

use App\Entity\DiveLog;
use App\Entity\DiveLogPhoto;
use App\Message\DiveLogMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class DiveLogHandler
{
    public function __construct(private EntityManagerInterface $em) {}

    public function __invoke(DiveLogMessage $message): void
    {
        $log = new DiveLog();

        $date = \DateTime::createFromFormat('Y-m-d', $message->getDate());
        $log->setDate($date);

        $log->setLocation($message->getLocation());
        $log->setNotes($message->getNotes() ?? '');
        $log->setMaxDepth($message->getMaxDepth());
        $log->setCourse($message->getCourse() ?? '');
        $log->setDuration($message->getDuration());

        foreach ($message->getImages() as $img) {
            $photo = new DiveLogPhoto();
            $photo->setFilename($img['filename']);
            $photo->setOriginalName($img['originalName'] ?? null);

            $log->addDiveLogPhoto($photo);
        }

        $this->em->persist($log);
        $this->em->flush();
    }
}
