<?php

namespace App\Controller\EleveController\TableauDeBord;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExercerController extends AbstractController
{
    #[Route('/exercer', name: 'app.exercer')]
    public function index(): Response
    {
        return $this->render('Eleve/dashboards/exercer.html.twig', [
            'controller_name' => 'ExercerController',
        ]);
    }
}
