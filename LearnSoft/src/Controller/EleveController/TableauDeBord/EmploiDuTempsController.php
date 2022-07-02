<?php

namespace App\Controller\EleveController\TableauDeBord;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmploiDuTempsController extends AbstractController
{
    #[Route('/emploi_temps', name: 'app.emploi.temps')]
    public function index(): Response
    {
        return $this->render('Eleve/apps/calendrier.html.twig', [
            'controller_name' => 'EmploiDuTempsController',
        ]);
    }
}
