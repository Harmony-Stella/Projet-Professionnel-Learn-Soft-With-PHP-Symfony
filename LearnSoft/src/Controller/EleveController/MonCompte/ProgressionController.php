<?php

namespace App\Controller\EleveController\MonCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Parents;
use App\Entity\Evaluation;
use App\Entity\Sujet;
use App\Entity\Propositions;
use App\Entity\EleveSujet;
use App\Entity\EleveEvaluation;
use App\Entity\EleveProposition;
use App\Repository\EleveRepository;
use App\Repository\MatiereRepository;
use App\Repository\ClasseRepository;
use App\Repository\EvaluationRepository;
use App\Repository\SujetRepository;
use App\Repository\PropositionsRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\EleveEvaluationRepository;
use App\Repository\EleveSujetRepository;
use App\Repository\ElevePropositionRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ProgressionController extends AbstractController
{
    public function __construct(EleveRepository $repoEleve,EvaluationRepository $repoEvaluation,SujetRepository $repoSujet,PropositionsRepository $repoProposition,MatiereRepository $repoMatiere,ClasseRepository $repoClasse,UtilisateurRepository $repoUtilisateur,EntityManagerInterface $em,EleveEvaluationRepository $repoEleveEvaluation,EleveSujetRepository $repoEleveSujet,ElevePropositionRepository $repoEleveProposition)
    {
      
         $this->repoEleve = $repoEleve;
         $this->repoEleveProposition = $repoEleveProposition;
         $this->repoEleveSujet = $repoEleveSujet;
         $this->repoUtilisateur = $repoUtilisateur;
         $this->repoEleveEvaluation = $repoEleveEvaluation;
         $this->repoEvaluation = $repoEvaluation;
         $this->repoSujet = $repoSujet;
         $this->repoProposition = $repoProposition;
         $this->repoMatiere = $repoMatiere;
         $this->repoClasse = $repoClasse;
         $this->em = $em;

    }

    #[Route('/compte/progression', name: 'app.compte.progression')]
    public function index(): Response
    {
        
        return $this->render('Eleve/account/progression.html.twig', [
            'controller_name' => 'ProgressionController',
        ]);
    }
}
