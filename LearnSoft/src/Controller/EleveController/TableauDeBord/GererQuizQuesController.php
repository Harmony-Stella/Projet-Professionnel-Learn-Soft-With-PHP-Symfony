<?php

namespace App\Controller\EleveController\TableauDeBord;

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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

class GererQuizQuesController extends AbstractController
{
    public function __construct(AuthorizationCheckerInterface $authorizationChecker,EleveRepository $repoEleve,EvaluationRepository $repoEvaluation,SujetRepository $repoSujet,PropositionsRepository $repoProposition,MatiereRepository $repoMatiere,ClasseRepository $repoClasse,UtilisateurRepository $repoUtilisateur,EntityManagerInterface $em,EleveEvaluationRepository $repoEleveEvaluation,EleveSujetRepository $repoEleveSujet,ElevePropositionRepository $repoEleveProposition)
    {
      
         $this->repoEleve = $repoEleve;
         $this->repoEleveProposition = $repoEleveProposition;
         $this->repoEleveSujet = $repoEleveSujet;
         $this->repoUtilisateur = $repoUtilisateur;
         $this->repoEleveEvaluation = $repoEleveEvaluation;
         $this->repoEvaluation = $repoEvaluation;
         $this->authorizationChecker = $authorizationChecker;
         $this->repoSujet = $repoSujet;
         $this->repoProposition = $repoProposition;
         $this->repoMatiere = $repoMatiere;
         $this->repoClasse = $repoClasse;
         $this->em = $em;

    }


    #[Route('/gestionQuiz', name: 'app.gestionQuiz')]
    public function index(Security $security,): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            
            return $this->redirectToRoute('app.connexion');
            
        }
        else{
            if($this->authorizationChecker->isGranted('ROLE_PARENT')){

                return $this->redirectToRoute('apps.accueil');
                
            }
        }
        $eleve = $this->getUser()->getEleve();
        //dd($eleve->getUtilisateurs());
        $EleveEvaluation = $this->repoEleveEvaluation->EleveEvaluation($eleve->getId());
        //dd($EleveEvaluation);
        return $this->render('Eleve/dashboards/gestion-quiz.html.twig', [
            'controller_name' => 'GererQuizController',
            'eleveEvaluation'=>$EleveEvaluation
        ]);
    }
     #[Route('/gestionQuest', name: 'app.gestionQuest')]
    public function quest(Security $security,): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            
            return $this->redirectToRoute('app.connexion');
            
        }
        else{
            if($this->authorizationChecker->isGranted('ROLE_PARENT')){

                return $this->redirectToRoute('apps.accueil');
                
            }
        } if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            
            return $this->redirectToRoute('app.connexion');
            
        }
        else{
            if($this->authorizationChecker->isGranted('ROLE_PARENT')){

                return $this->redirectToRoute('apps.accueil');
                
            }
        }

        $eleve = $this->getUser()->getEleve();
        $EleveEvaluation = $this->repoEleveEvaluation->EleveEvaluation($eleve->getId());
        $MoiUtilisateur = $this->repoUtilisateur->MoiUtilisateur($eleve->getId());

        if ($EleveEvaluation == null ) {
            $EleveEvaluation=0;
        }

        foreach ($MoiUtilisateur as $key => $value) {
            
            $MoiClasse=$value->getClasse();
            
        }

        $ClasseEvaluation = $this->repoEvaluation->ClasseEvaluation($MoiClasse->getId());
        //dd($ClasseEvaluation);
        return $this->render('Eleve/dashboards/gestion-quest.html.twig', [
            'controller_name' => 'GererQuestController',
            'classeEvaluation'=>$ClasseEvaluation
        ]);
    }
}
