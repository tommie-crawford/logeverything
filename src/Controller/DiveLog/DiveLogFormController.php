<?php

namespace App\Controller\DiveLog;

use App\Entity\DiveLog;
use App\Form\DiveLogType;
use App\Service\DiveLogManager;
use App\Service\PhotoUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class DiveLogFormController extends AbstractController
{
    public function __construct(
        private readonly DiveLogManager $manager,
        private readonly MessageBusInterface $messageBus,
        private readonly PhotoUploadService $photoUploadService,
    ) {}

    #[Route('/duiklog/nieuw', name: 'app_divelog_new')]
    public function new(Request $request): Response
    {
        $diveLog = new DiveLog();
        $form = $this->createForm(DiveLogType::class, $diveLog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $form->get('images')->getData() ?? [];
            $images = $this->photoUploadService->store($files);

            $message = $this->manager->createMessage($diveLog, $images);
            $this->messageBus->dispatch($message);

            $this->addFlash('success', 'Duik toegevoegd!');

            return $this->redirectToRoute('duiklog_overzicht');
        }

        return $this->render('divelog/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/duiklog/{id}/bewerken', name: 'app_divelog_edit')]
    public function edit(DiveLog $diveLog, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(DiveLogType::class, $diveLog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Duik bijgewerkt!');

            return $this->redirectToRoute('duiklog_overzicht');
        }

        return $this->render('divelog/form.html.twig', [
            'form' => $form->createView(),
            'divelog' => $diveLog,
        ]);
    }

    #[Route('/duiklog/{id}/verwijderen', name: 'app_divelog_delete', methods: ['POST'])]
    public function delete(DiveLog $diveLog, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('delete_divelog_'.$diveLog->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Ongeldig CSRF token.');

            return $this->redirectToRoute('duiklog_overzicht');
        }

        $em->remove($diveLog);
        $em->flush();

        $this->addFlash('success', 'Duik verwijderd!');

        return $this->redirectToRoute('duiklog_overzicht');
    }
}
