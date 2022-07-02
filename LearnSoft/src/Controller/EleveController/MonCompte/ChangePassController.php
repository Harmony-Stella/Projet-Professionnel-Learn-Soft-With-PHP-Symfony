<?php

namespace App\Controller\EleveController\MonCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChangePassController extends AbstractController
{
    #[Route('/auth/changepass', name: 'app.auth.changepass')]
    public function index(): Response
    {
        return $this->render('Eleve/account/change-pass.html.twig', [
            'controller_name' => 'ChangePassController',
        ]);
    }
}
