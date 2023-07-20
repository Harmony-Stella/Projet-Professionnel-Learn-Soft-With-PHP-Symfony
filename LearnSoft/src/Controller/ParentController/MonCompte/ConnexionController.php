<?php

namespace App\Controller\ParentController\MonCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    #[Route('/parent/compteConnexion', name: 'apps.compte.connexion')]
    public function index(): Response
    {
        return $this->render('Parent/account/connexion.html.twig', [
            'controller_name' => 'ConnexionController',
        ]);
    }
}
