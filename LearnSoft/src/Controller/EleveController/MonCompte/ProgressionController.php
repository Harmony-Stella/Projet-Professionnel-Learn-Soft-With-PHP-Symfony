<?php

namespace App\Controller\EleveController\MonCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgressionController extends AbstractController
{
    #[Route('/compte/progression', name: 'app.compte.progression')]
    public function index(): Response
    {
        return $this->render('Eleve/account/progression.html.twig', [
            'controller_name' => 'ProgressionController',
        ]);
    }
}
