<?php

namespace App\Controller\ParentController\TableauDeBord;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Parents;
use App\Entity\Evaluation;
use App\Entity\Sujet;
use App\Entity\Propositions;
use App\Repository\EleveRepository;
use App\Repository\MatiereRepository;
use App\Repository\ClasseRepository;
use App\Repository\EvaluationRepository;
use App\Repository\SujetRepository;
use App\Repository\PropositionsRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\Repository\DeconnexionRepository;
use App\Repository\EleveEvaluationRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class GererQuizQuestController extends AbstractController
{


    public function __construct(AuthorizationCheckerInterface $authorizationChecker,EntityManagerInterface $em,EleveRepository $repoEleve,EvaluationRepository $repoEvaluation,SujetRepository $repoSujet,PropositionsRepository $repoProposition,MatiereRepository $repoMatiere,ClasseRepository $repoClasse,DeconnexionRepository $repoDeconnexion,EleveEvaluationRepository $repoEleveEvaluation)
    {
      
         $this->repoEleve = $repoEleve;
         $this->repoEvaluation = $repoEvaluation;
         $this->repoEleveEvaluation = $repoEleveEvaluation;
         $this->repoSujet = $repoSujet;
         $this->repoProposition = $repoProposition;
         $this->repoMatiere = $repoMatiere;
         $this->repoClasse = $repoClasse;
         $this->repoDeconnexion = $repoDeconnexion;
         $this->authorizationChecker = $authorizationChecker;
         $this->em = $em;

    }
    
    #[Route('/parent/gestionQuiz', name: 'apps.gestionQuiz')]
    public function index(Security $security,): Response
    {
        $parent = $this->getUser()->getParent();
        $MesEleves = $this->repoEleve->MesEleves($parent->getId());
        $LoginParent = $this->repoDeconnexion->LoginParent($parent);

        foreach ($MesEleves as $key => $value) {
            $Login = $this->repoDeconnexion->Login($value->getId());
            
            if($Login!=null){
            $LoginOne = $this->repoDeconnexion->LoginOne($value->getId());
            foreach ($value->getUtilisateurs() as $keys => $values) {
                $values->setOnline($LoginOne);
                $this->em->persist($value);
                $this->em->flush();
            }
       }
        }

        
        //dd($eleve->getUtilisateurs());
         //dd($parent->getEleves());
        //$EleveEvaluation = $this->repoEleveEvaluation->EleveEvaluation($parent->getEleves()->getId());
        $EleveEvaluationParent = $this->repoEvaluation->LesEvaluations($parent->getId());
       //dd($EleveEvaluationParent);
        
        return $this->render('Parent/dashboards/gestion-quiz.html.twig', [
            'controller_name' => 'GererQuizQuestController',
            'parent'=>$parent,
            'mesEleves'=>$MesEleves,
            'LoginParent'=>$LoginParent,
            'eleveEvaluationParent'=>$EleveEvaluationParent
        ]);
    }
    #[Route('/parent/gestionQuest', name: 'apps.gestionQuest')]
    public function quest(Security $security,): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            
            return $this->redirectToRoute('app.connexion');
            
        }
        else{
            if($this->authorizationChecker->isGranted('ROLE_ELEVE')){

                return $this->redirectToRoute('app.connexion');
                
            }
        }
        $parent = $this->getUser()->getParent();
        $MesEleves = $this->repoEleve->MesEleves($parent->getId());
        $LoginParent = $this->repoDeconnexion->LoginParent($parent);
        foreach ($MesEleves as $key => $value) {
            $Login = $this->repoDeconnexion->Login($value->getId());
            
            if($Login!=null){
            $LoginOne = $this->repoDeconnexion->LoginOne($value->getId());
            foreach ($value->getUtilisateurs() as $keys => $values) {
                $values->setOnline($LoginOne);
                $this->em->persist($value);
                $this->em->flush();
            }
       }
        }
        return $this->render('Parent/dashboards/gestion-quest.html.twig', [
            'controller_name' => 'GererQuizQuestController',
            'parent'=>$parent,
            'mesEleves'=>$MesEleves,
            'LoginParent'=>$LoginParent
        ]);
    }
}
