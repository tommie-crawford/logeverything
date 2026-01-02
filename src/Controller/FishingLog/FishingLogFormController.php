<?php

namespace App\Controller\FishingLog;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
Use App\Entity\FishingLog;
use App\Form\FishingLogType;
use App\Service\FishingLogManager;
Use Symfony\Component\Messenger\MessageBusInterface;
use App\Service\PhotoUploadService;

class FishingLogFormController extends AbstractController
{
    #[Route('/vissen/nieuw', name: 'app_fishinglog_new')]
    public function new(Request $request, FishingLogManager $manager, MessageBusInterface $messageBus, PhotoUploadService $photoUploadService): Response
    {
        $fishingLog = new FishingLog();

        $form = $this->createForm(FishingLogType::class, $fishingLog);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $form->get('images')->getData() ?? [];

            $images = $photoUploadService->store($files);

            $message = $manager->createMessage($fishingLog, $images);
            $messageBus->dispatch($message);

            $this->addFlash('success', 'Vangst toegevoegd!');

            return $this->redirectToRoute('app_fishinglog_new');
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
        if ($form->isSubmitted() && !$form->isValid()) {
            dd((string) $form->getErrors(true, false));
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush(); // entity is already managed

            $this->addFlash('success', 'Vangst bijgewerkt!');

            return $this->redirectToRoute('app_fishinglog_edit', ['id' => $fishingLog->getId()]);
        }

        return $this->render('fishinglog/form.html.twig', [
            'form' => $form->createView(),
            'fishinglog' => $fishingLog,
        ]);
    }

    #[Route('/vissen/{id}/verwijderen', name: 'app_fishinglog_delete', methods: ['POST'])]
    public function delete(FishingLog $fishingLog, Request $request, EntityManagerInterface $em): Response
    {
        // CSRF check
        if ($this->isCsrfTokenValid('delete_fishinglog_'.$fishingLog->getId(), $request->request->get('_token'))) {
            $em->remove($fishingLog);
            $em->flush();

            $this->addFlash('success', 'Vangst verwijderd!');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('vissen_overzicht');
    }

}
