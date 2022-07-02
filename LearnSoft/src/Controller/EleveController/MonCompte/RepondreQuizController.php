<?php

namespace App\Controller\EleveController\MonCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RepondreQuizController extends AbstractController
{
    #[Route('/entrainer/repondre_quiz', name: 'app.entrainer.repondre_quiz')]
    public function index(): Response
    {
        return $this->render('Eleve/account/repondre-quiz.html.twig', [
            'controller_name' => 'RepondreQuizController',
        ]);
    }
}
