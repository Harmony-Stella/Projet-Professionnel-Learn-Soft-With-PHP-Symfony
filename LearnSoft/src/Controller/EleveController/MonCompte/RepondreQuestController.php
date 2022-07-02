<?php

namespace App\Controller\EleveController\MonCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RepondreQuestController extends AbstractController
{
    #[Route('/entrainer/repondre_quest', name: 'app.entrainer.repondre_quest')]
    public function index(): Response
    {
        return $this->render('Eleve/account/repondre-quest.html.twig', [
            'controller_name' => 'RepondreQuestController',
        ]);
    }
}
