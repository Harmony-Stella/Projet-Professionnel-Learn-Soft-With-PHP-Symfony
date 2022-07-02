<?php

namespace App\Controller\EleveController\MonCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParametresController extends AbstractController
{
    #[Route('/compte/parametres', name: 'app.compte.parametres')]
    public function index(): Response
    {
        return $this->render('Eleve/account/parametres.html.twig', [
            'controller_name' => 'ParametresController',
        ]);
    }
}
