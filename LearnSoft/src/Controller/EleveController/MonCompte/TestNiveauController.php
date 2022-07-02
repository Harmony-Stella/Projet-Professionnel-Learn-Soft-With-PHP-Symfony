<?php

namespace App\Controller\EleveController\MonCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestNiveauController extends AbstractController
{
    #[Route('/auth/testniveau', name: 'app.auth.testniveau')]
    public function index(): Response
    {
        return $this->render('Eleve/account/test-niveau.html.twig', [
            'controller_name' => 'TestNiveauController',
        ]);
    }
}
