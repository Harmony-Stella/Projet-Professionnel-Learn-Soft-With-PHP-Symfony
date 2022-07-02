<?php

namespace App\Controller\EleveController\TableauDeBord;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GererQuizQuesController extends AbstractController
{
    #[Route('/gestionqq', name: 'app.gestionqq')]
    public function index(): Response
    {
        return $this->render('Eleve/dashboards/gestionqq.html.twig', [
            'controller_name' => 'GererQuizQuesController',
        ]);
    }
}
