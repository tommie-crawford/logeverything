<?php

namespace App\Controller\DiveLog;

use App\Repository\DiveLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/duiklog/overzicht', name: 'duiklog_overzicht')]
    public function dashboard(DiveLogRepository $diveLogRepository): Response
    {
        $dives = $diveLogRepository->findBy([], ['date' => 'DESC']);

        return $this->render('/divelog/dashboard.html.twig', ['dives' => $dives]);
    }
}
