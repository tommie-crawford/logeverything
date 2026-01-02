<?php

namespace App\MessageHandler;

use App\Entity\FishingLog;
use App\Entity\FishingLogPhoto;
use App\Message\FishingLogMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class FishingLogHandler
{
    public function __construct(private EntityManagerInterface $em) {}

    public function __invoke(FishingLogMessage $message): void
    {
        $log = new FishingLog();

        $date = \DateTime::createFromFormat('Y-m-d', $message->getDate());
        $log->setDate($date);

        $log->setType($message->getType());
        $log->setWeight($message->getWeight());
        $log->setLength($message->getLength());

        foreach ($message->getImages() as $img) {
            $photo = new FishingLogPhoto();
            $photo->setFileName($img['filename']);
            $photo->setOriginalName($img['originalName'] ?? '');

            $log->addPhoto($photo);
        }

        $this->em->persist($log);
        $this->em->flush();
    }
}

