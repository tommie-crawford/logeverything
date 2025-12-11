<?php

namespace App\Controller\DiveLog;

use App\Message\DiveLogMessage;
use Doctrine\ORM\EntityManagerInterface;
use http\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
Use App\Entity\DiveLog;
use App\Form\DiveLogType;
use App\Service\DiveLogManager;
Use Symfony\Component\Messenger\MessageBusInterface;
use App\Service\PhotoUploadService;

class DiveLogFormController extends AbstractController
{
    #[Route('/divelog/new', name: 'app_divelog_new')]
    public function new(Request $request, DiveLogManager $manager, MessageBusInterface $messageBus, PhotoUploadService $photoUploadService): Response
    {
        $divelog = new DiveLog();

        $form = $this->createForm(DiveLogType::class, $divelog);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $files = $form->get('images')->getData() ?? [];

            $images = $photoUploadService->store($files);

            $message = $manager->createMessage($divelog, $images);
            $messageBus->dispatch($message);

            $this->addFlash('success', 'Dive added!');

            return $this->redirectToRoute('app_divelog_new');
        }

        return $this->render('divelog/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/divelog/{id}/edit', name: 'app_divelog_edit')]
    public function edit(Divelog $divelog, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(DivelogType::class, $divelog);
        $form->handleRequest($request);
        if ($form->isSubmitted() && !$form->isValid()) {
            dd((string) $form->getErrors(true, false));
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush(); // entity is already managed

            $this->addFlash('success', 'Dive updated!');

            return $this->redirectToRoute('app_divelog_edit', ['id' => $divelog->getId()]);
        }

        return $this->render('divelog/form.html.twig', [
            'form' => $form->createView(),
            'divelog' => $divelog,
        ]);
    }

    #[Route('/divelog/{id}/delete', name: 'app_divelog_delete', methods: ['POST'])]
    public function delete(DiveLog $diveLog, Request $request, EntityManagerInterface $em): Response
    {
        // CSRF check
        if ($this->isCsrfTokenValid('delete_divelog_'.$diveLog->getId(), $request->request->get('_token'))) {
            $em->remove($diveLog);
            $em->flush();

            $this->addFlash('success', 'Dive deleted!');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('dashboard');
    }

}
