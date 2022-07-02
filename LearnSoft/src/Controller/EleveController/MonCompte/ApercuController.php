<?php

namespace App\Controller\EleveController\MonCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApercuController extends AbstractController
{
    #[Route('/compte/apercu', name: 'app.compte.apercu')]
    public function index(): Response
    {
        return $this->render('Eleve/account/apercu.html.twig', [
            'controller_name' => 'ApercuController',
        ]);
    }
}
