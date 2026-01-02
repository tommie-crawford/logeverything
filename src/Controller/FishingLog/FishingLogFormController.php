<?php

namespace App\Controller\FishingLog;

use App\Entity\FishingLog;
use App\Form\FishingLogType;
use App\Service\FishingLogManager;
use App\Service\PhotoUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class FishingLogFormController extends AbstractController
{
    public function __construct(
        private readonly FishingLogManager $manager,
        private readonly MessageBusInterface $messageBus,
        private readonly PhotoUploadService $photoUploadService,
    ) {}

    #[Route('/vissen/nieuw', name: 'app_fishinglog_new')]
    public function new(Request $request): Response
    {
        $fishingLog = new FishingLog();
        $form = $this->createForm(FishingLogType::class, $fishingLog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $form->get('images')->getData() ?? [];
            $images = $this->photoUploadService->store($files);

            $message = $this->manager->createMessage($fishingLog, $images);
            $this->messageBus->dispatch($message);

            $this->addFlash('success', 'Vangst toegevoegd!');

            return $this->redirectToRoute('vissen_overzicht');
        }

        return $this->render('fishinglog/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/vissen/{id}/bewerken', name: 'app_fishinglog_edit')]
    public function edit(FishingLog $fishingLog, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(FishingLogType::class, $fishingLog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Vangst bijgewerkt!');

            return $this->redirectToRoute('vissen_overzicht');
        }

        return $this->render('fishinglog/form.html.twig', [
            'form' => $form->createView(),
            'fishinglog' => $fishingLog,
        ]);
    }

    #[Route('/vissen/{id}/verwijderen', name: 'app_fishinglog_delete', methods: ['POST'])]
    public function delete(FishingLog $fishingLog, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('delete_fishinglog_'.$fishingLog->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Ongeldig CSRF token.');

            return $this->redirectToRoute('vissen_overzicht');
        }

        $em->remove($fishingLog);
        $em->flush();

        $this->addFlash('success', 'Vangst verwijderd!');

        return $this->redirectToRoute('vissen_overzicht');
    }
}
