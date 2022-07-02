<?php

namespace App\Controller\EleveController\MonCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    #[Route('/auth/connexion', name: 'app.auth.connexion')]
    public function index(): Response
    {
        return $this->render('Eleve/account/connexion.html.twig', [
            'controller_name' => 'ConnexionController',
        ]);
    }
}
