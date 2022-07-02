<?php

namespace App\Controller\EleveController\TableauDeBord;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcceuilController extends AbstractController
{
    #[Route('/', name: 'app.acceuil')]
    public function index(): Response
    {
        return $this->render('Eleve/index.html.twig', [
            'controller_name' => 'AcceuilController',
        ]);
    }
}
