<?php

namespace App\Controller\EleveController\MonCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResultatsController extends AbstractController
{
    #[Route('/compte/resultats', name: 'app.compte.resultats')]
    public function index(): Response
    {
        return $this->render('Eleve/account/resultats.html.twig', [
            'controller_name' => 'ResultatsController',
        ]);
    }
}
