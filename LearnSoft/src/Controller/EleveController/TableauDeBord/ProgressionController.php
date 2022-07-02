<?php

namespace App\Controller\EleveController\TableauDeBord;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgressionController extends AbstractController
{
    #[Route('/progression', name: 'app.progression')]
    public function index(): Response
    {
        return $this->render('Eleve/dashboards/progression.html.twig', [
            'controller_name' => 'ProgressionController',
        ]);
    }
}
