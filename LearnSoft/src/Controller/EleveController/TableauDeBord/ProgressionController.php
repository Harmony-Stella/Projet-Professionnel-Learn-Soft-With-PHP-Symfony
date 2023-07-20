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
class ProgressionController extends AbstractController
{

    public function __construct(EleveRepository $repoEleve,EvaluationRepository $repoEvaluation,SujetRepository $repoSujet,PropositionsRepository $repoProposition,MatiereRepository $repoMatiere,ClasseRepository $repoClasse,UtilisateurRepository $repoUtilisateur,EntityManagerInterface $em,EleveEvaluationRepository $repoEleveEvaluation,EleveSujetRepository $repoEleveSujet,ElevePropositionRepository $repoEleveProposition, ProgressionJourRepository $repoProgressionJour)
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
         $this->em = $em;

    }

    #[Route('/progression', name: 'app.progression')]
    public function index(): Response
    {
        $eleve = $this->getUser()->getEleve();
        $utilisateurs=$eleve->getUtilisateurs();
        foreach ($utilisateurs as $key => $value) {
            
            $CountEvaluation = $this->repoEvaluation->CountEvaluation($value->getClasse());
            $matieres = $this->repoMatiere->Matieres($value->getClasse());

        }

        $CountEleveEvaluation = $this->repoEleveEvaluation->CountEleveEvaluation($eleve->getId());
        
        $NoteEleveEvaluation = $this->repoEleveEvaluation->NoteEleveEvaluation($eleve->getId());

        $note=0;

        foreach ($NoteEleveEvaluation as $key => $value) {
            
            $note=$note+$value->getSur20();
        }

        $EleveEvaluation = $this->repoEleveEvaluation->EleveEvaluation($eleve->getId());
        //dd($EleveEvaluation);
        $noteRecu=0;
        $iteration=0;
        $item=0;
        global $progression;
        

        foreach ($EleveEvaluation as $key => $value){
           $dateEnd=strtotime($value->getEndAt());
           $dateEnd=date('Y-m-d',$dateEnd);

           
           $matiere=$value->getEvaluation()->getMatiere();
           
           $progressionJour = new ProgressionJour;
               foreach ($EleveEvaluation as $keys => $values){
                $item=$keys+1;
                $val=strtotime($value->getEndAt());
                $val=date('Y-m-d',$val);
              //dd($item);
                //dd($dateEnd.'-'.$val);
                    if ($dateEnd==$val AND $matiere==$values->getEvaluation()->getMatiere()) {
                        $iteration=$iteration+1;
                        $noteRecu=$noteRecu+$values->getSur20();

                        if ($item==sizeof($EleveEvaluation)) {
                            $valeurTotale=$iteration*20;
                            $progression=100*$noteRecu/$valeurTotale;
                            $Progression = $this->repoProgressionJour->Progression($dateEnd);
                            if ($Progression==null) {
                                $progressionJour->setDateDuJour($dateEnd);
                                $progressionJour->setProgression($progression);
                                $progressionJour->setEleve($eleve);
                                $progressionJour->setMatiere($matiere);
                                $this->em->persist($progressionJour);
                                $this->em->flush();
                        }

                    }

                    }
                    elseif ($item==sizeof($EleveEvaluation)) {
                        $valeurTotale=$iteration*20;
                        $progression=100*$noteRecu/$valeurTotale;
                        //dd($dateEnd);
                        $Progression = $this->repoProgressionJour->Progression($dateEnd);
                        if ($Progression==null) {
                            $progressionJour->setDateDuJour($dateEnd);
                            $progressionJour->setProgression($progression);
                            $progressionJour->setEleve($eleve);
                            $progressionJour->setMatiere($matiere);
                            $this->em->persist($progressionJour);
                            $this->em->flush();
                        }
                        elseif ($Progression!=null) {
                            foreach ($Progression as $keyy => $valuee) {
                                $valuee->setDateDuJour($dateEnd);
                                $valuee->setProgression($progression);
                                $valuee->setEleve($eleve);
                                $valuee->setMatiere($matiere);
                                $this->em->persist($valuee);
                                $this->em->flush();
                            }
                            
                        }

                    }
               
                }

            //dd($progression);
            

        }

        $Performance = $this->repoProgressionJour->Performance($eleve->getId());

        if ($CountEleveEvaluation==null) {
            $NoSubmit=$CountEvaluation;
            $PourcentageNoSubmit=100*$NoSubmit/$CountEvaluation;
            $moyenne=(ceil(abs($note/1)));
            $PourcentageEvaluation=100*$CountEleveEvaluation/1;

        }

        else {
            $NoSubmit=$CountEvaluation-$CountEleveEvaluation;
            $PourcentageNoSubmit=100*$NoSubmit/$CountEvaluation;
            $moyenne=(ceil(abs($note/$CountEleveEvaluation)));
            $PourcentageEvaluation=100*$CountEleveEvaluation/$CountEvaluation;

        }
        
        
        
        
        //dd($CountEvaluation);
        return $this->render('Eleve/dashboards/progression.html.twig', [
            'controller_name' => 'ProgressionController',
            'countEvaluation'=>$CountEvaluation,
            'countEleveEvaluation'=>$CountEleveEvaluation,
            'pourcentageEvaluation'=>$PourcentageEvaluation,
            'pourcentageNoSubmit'=>$PourcentageNoSubmit,
            'noSubmit'=>$NoSubmit,
            'moyenne'=>$moyenne,
            'matieres'=>$matieres,
            'progression'=>$progression,
            'performance'=>$Performance
        ]);
    }
}
