<?php

namespace App\Controller\EleveController\MonCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizQuestController extends AbstractController
{
    #[Route('/compte/quizquest', name: 'app.compte.quizquest')]
    public function index(): Response
    {
        return $this->render('Eleve/account/quizquest.html.twig', [
            'controller_name' => 'QuizQuestController',
        ]);
    }
}
