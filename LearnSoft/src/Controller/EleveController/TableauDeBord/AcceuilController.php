<?php

namespace App\Controller\EleveController\TableauDeBord;

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
use App\Repository\ElevePropositionRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\Entity\Deconnexion;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AcceuilController extends AbstractController
{

    public function __construct(EleveRepository $repoEleve,AuthorizationCheckerInterface $authorizationChecker,EvaluationRepository $repoEvaluation,SujetRepository $repoSujet,PropositionsRepository $repoProposition,MatiereRepository $repoMatiere,ClasseRepository $repoClasse,UtilisateurRepository $repoUtilisateur,EntityManagerInterface $em,EleveEvaluationRepository $repoEleveEvaluation,EleveSujetRepository $repoEleveSujet,ElevePropositionRepository $repoEleveProposition, ProgressionJourRepository $repoProgressionJour)
    {
      
         $this->repoEleve = $repoEleve;
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
         $this->authorizationChecker = $authorizationChecker;
         $this->em = $em;

    }
    #[Route('/', name: 'app.acceuil')]
    public function index(Security $security,Request $request): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            
            return $this->redirectToRoute('app.connexion');
            
        }
        else{
            if($this->authorizationChecker->isGranted('ROLE_PARENT')){

                return $this->redirectToRoute('apps.accueil');
                
            }
        }

        $verif=$request->request->get('verif');
        
            // code...
        
            $eleve = $this->getUser()->getEleve();
            $deconnexion= new Deconnexion;
            $temps=date('D, d F Y H:i');
           $aip="8.8.8.8";
        //dd($aip);
        $details=json_decode(file_get_contents("http://ipinfo.io/{$aip}/json"));
            //dd($details);
            $deconnexion->setAdresseIp($_SERVER['REMOTE_ADDR']);
            $deconnexion->setStatus(1);
            $deconnexion->setTempsMis($temps);
            $deconnexion->setLocalisation($details->city);
            $deconnexion->setDevice("Windows10");
            $deconnexion->setEleve($eleve);
        
        $this->em->persist($deconnexion);
        $this->em->flush();
    

    return $this->render('Eleve/index.html.twig', [
            'controller_name' => 'AcceuilController',
        ]);
    
    }
    #[Route('/offline', name: 'app.offline')]
    public function offline(Security $security,): Response
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
        $temps=date('D, d F Y H:i');
        $deconnexion= new Deconnexion;
        $aip="8.8.8.8";
        //dd($aip);
        $details=json_decode(file_get_contents("http://ipinfo.io/{$aip}/json"));
        //dd($details->city);
        $deconnexion->setAdresseIp($_SERVER['REMOTE_ADDR']);
        $deconnexion->setStatus(0);
        $deconnexion->setTempsMis($temps);
        $deconnexion->setLocalisation($details->city);
        $deconnexion->setDevice("Windows10");
        $deconnexion->setEleve($eleve);
        //dd($deconnexion);
        $this->em->persist($deconnexion);
        $this->em->flush();

        
        
    return $this->redirectToRoute('app.logout');
    
    }
}
