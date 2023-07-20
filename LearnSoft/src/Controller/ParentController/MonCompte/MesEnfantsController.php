<?php

namespace App\Controller\ParentController\MonCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Parents;
use App\Entity\Evaluation;
use App\Entity\Sujet;
use App\Entity\Propositions;
use App\Entity\ProgressionJour;
use App\Entity\EleveSujet;
use App\Entity\EleveEvaluation;
use App\Entity\EleveProposition;
use App\Repository\EleveRepository;
use App\Repository\ProgressionJourRepository;
use App\Repository\MatiereRepository;
use App\Repository\ClasseRepository;
use App\Repository\EvaluationRepository;
use App\Repository\SujetRepository;
use App\Repository\PropositionsRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\EleveEvaluationRepository;
use App\Repository\EleveSujetRepository;
use App\Repository\DeconnexionRepository;
use App\Repository\ElevePropositionRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MesEnfantsController extends AbstractController
{
        public function __construct(AuthorizationCheckerInterface $authorizationChecker,EleveRepository $repoEleve,EvaluationRepository $repoEvaluation,SujetRepository $repoSujet,PropositionsRepository $repoProposition,MatiereRepository $repoMatiere,ClasseRepository $repoClasse,UtilisateurRepository $repoUtilisateur,EntityManagerInterface $em,EleveEvaluationRepository $repoEleveEvaluation,EleveSujetRepository $repoEleveSujet,ElevePropositionRepository $repoEleveProposition, ProgressionJourRepository $repoProgressionJour,DeconnexionRepository $repoDeconnexion)
    {
      
         $this->repoEleve = $repoEleve;
         $this->authorizationChecker = $authorizationChecker;
         $this->repoDeconnexion = $repoDeconnexion;
         $this->repoEleveProposition = $repoEleveProposition;
         $this->repoProgressionJour = $repoProgressionJour;
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
    #[Route('/parent/compteMesEnfants', name: 'apps.compte.mes_enfants')]
    public function index(Security $security,): Response
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

        return $this->render('Parent/account/mes-enfants.html.twig', [
            'controller_name' => 'MesEnfantsController',
            'parent'=>$parent,
            'mesEleves'=>$MesEleves,
            'LoginParent'=>$LoginParent
        ]);
    }
}
