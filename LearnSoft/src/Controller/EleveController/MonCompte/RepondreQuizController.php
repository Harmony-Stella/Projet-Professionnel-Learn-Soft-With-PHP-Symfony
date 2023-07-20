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
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RepondreQuizController extends AbstractController
{
    private $em;
    public function __construct(AuthorizationCheckerInterface $authorizationChecker,EleveRepository $repoEleve,EvaluationRepository $repoEvaluation,SujetRepository $repoSujet,PropositionsRepository $repoProposition,MatiereRepository $repoMatiere,ClasseRepository $repoClasse,UtilisateurRepository $repoUtilisateur,EntityManagerInterface $em,EleveEvaluationRepository $repoEleveEvaluation,EleveSujetRepository $repoEleveSujet,ElevePropositionRepository $repoEleveProposition)
    {
      
         $this->repoEleve = $repoEleve;
         $this->repoEleveProposition = $repoEleveProposition;
         $this->repoEleveSujet = $repoEleveSujet;
         $this->repoUtilisateur = $repoUtilisateur;
         $this->authorizationChecker = $authorizationChecker;
         $this->repoEleveEvaluation = $repoEleveEvaluation;
         $this->repoEvaluation = $repoEvaluation;
         $this->repoSujet = $repoSujet;
         $this->repoProposition = $repoProposition;
         $this->repoMatiere = $repoMatiere;
         $this->repoClasse = $repoClasse;
         $this->em = $em;

    }

    #[Route('/entrainer/repondre_quiz', name: 'app.entrainer.repondre_quiz')]
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
        /*----------------Requetes SQL et recupérations */
        $eleve = $this->getUser()->getEleve();
        //dd($eleve->getUtilisateurs());
        $EleveEvaluation = $this->repoEleveEvaluation->EleveEvaluation($eleve->getId());
        //dd($EleveEvaluation);
        $MoiUtilisateur = $this->repoUtilisateur->MoiUtilisateur($eleve->getId());

        //dd($MoiUtilisateur);
        if ($EleveEvaluation == null ) {
            $EleveEvaluation=0;
        }

        /*foreach ($EleveEvaluation as $key => $value) {
              $heureActuelle = strtotime(date('H:i'));
              $duree=strtotime($value->getDureeRestant());
              //dd($duree.'-'.$heureActuelle);
              $heureFin=$heureActuelle+$duree;

              $dureeRestante=$heureFin-$heureActuelle;
              dd(date('H:i',$duree));

              $value->setDureeRestant(date('H:i',$dureeRestante));
        }*/

        

        //dd($EleveEvaluation);

        foreach ($MoiUtilisateur as $key => $value) {
            
            $MoiClasse=$value->getClasse();
            
        }

        //$MesEleves = $this->repoEleve->MesEleves($parent->getId());
        $ClasseEvaluation = $this->repoEvaluation->ClasseEvaluation($MoiClasse->getId());
        //dd($ClasseEvaluation);
        //dd($ClasseEvaluation);
        /*$LesMatieres = $this->repoMatiere->LesMatieres();
        $LesClasses = $this->repoClasse->LesClasses();*/
        foreach($ClasseEvaluation as $item=>$value){

            $DD=strtotime($value->getDateDebut());
            $DF=strtotime($value->getDateFin());

            $HD=strtotime($value->getHeureDebut());
            $HF=strtotime($value->getHeureFin());

            $dateFin=($value->getDateFin());
            $heureFin=($value->getHeureFin());

            $aujoudhui=date('Y-m-d');
            $heure=date('H:i');
            $aHeure=strtotime($heure);
           
            $aJour=strtotime($aujoudhui);

            $TT=(int)ceil(abs($DD-$aJour)/86400);


            if ($TT==0 AND $heure<$value->getHeureDebut()){
                $HH=ceil(($HD-$aHeure));
                $ah=date('H',$HH);
                $ai=date('i',$HH);
                //dd(date($ah."-".$ai));
                
                $value->setOuvreDans(date($ah.':'.$ai));
                $this->em->persist($value);
            }
            else{
                $value->setOuvreDans($TT."j");
                $this->em->persist($value);
            }

            if ($aujoudhui==$value->getDateDebut() AND $heure>=$value->getHeureDebut()) {
                $value->setStatut(1);
                $this->em->persist($value);   
            }
            else if ($aujoudhui>$value->getDateDebut()) {
                $value->setStatut(1);
                $this->em->persist($value);   
            }
            
            else {
                $value->setStatut(0);
                $this->em->persist($value);   
            }

            $iiii=0;

            if ($heure>=$value->getHeureFin() AND $aujoudhui>=$value->getDateFin()) {
                $value->setOverdue(1);
                $value->setStatut(0);
                $this->em->persist($value);
            }

            elseif ($aujoudhui>$value->getDateFin()) {
                $value->setOverdue(1);
                $value->setStatut(0);
                $this->em->persist($value);
            }

            else{
                $value->setOverdue(0);
                $this->em->persist($value);
            }
            
            if ($value->isStatut()==1 AND $value->isOverdue()==0) {
                $HH=ceil(($HF-$aHeure));
                $fermeA=(int)ceil(abs($DF-$aJour)/86400);
                //dd($fermeA);
                $ah=date('H',$HH);
                $ai=date('i',$HH);
                $b=date('Y-m-d',$fermeA);
                //sdd($a.'-'.$b);
                $value->setFermeDans($fermeA.'j'.$ah.'h'.$ai.'min');
                $this->em->persist($value);
            }   
        }

        $this->em->flush();

        if ($ClasseEvaluation==null) {
            $TT=0;
        }
  
        return $this->render('Eleve/account/repondre-quiz.html.twig', [
            'controller_name' => 'RepondreQuizController',
            'classeEvaluation'=>$ClasseEvaluation,
            'eleveEvaluation'=>$EleveEvaluation,
            '$aujoudhui'=>$aujoudhui

            
        ]);

    }

    #[Route('/entrainer/repondre_evaluation-{id}', name: 'app.entrainer.repondre_evaluation')]
    public function repondre(Security $security,Evaluation $evaluation,int $id,Request $request): Response
    {

        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            
            return $this->redirectToRoute('app.connexion');
            
        }
        else{
            if($this->authorizationChecker->isGranted('ROLE_PARENT')){

                return $this->redirectToRoute('apps.accueil');
                
            }
        }

    /*---------Queslques requestes de base (Recuperer une evaluation) et (Tout les sujets de cette evaluation) ainsi que le ( objet getUser() de eleve)----------*/
        $eleve = $this->getUser()->getEleve();
        $UneEvaluation = $this->repoEvaluation->UneEvaluation($id);
        $UneEleveEvaluation = $this->repoEleveEvaluation->UneEleveEvaluation($id,$eleve->getId());
        $LesSujets = $this->repoSujet->LesSujets($id);
        



    /*--------------------Création de nouvels objet--------------------------------*/
        $eleveEvaluation = new EleveEvaluation;
        $eleveSujet = new EleveSujet;
        $eleveProposition = new EleveProposition;
        $today = date('Y-m-d H:i');



    /*-------------------Prtsistance de l'objet EVALUATION------------------------------*/
        if ($UneEleveEvaluation==null) {
            $eleveEvaluation->setEvaluation($evaluation);
            $eleveEvaluation->setEleve($eleve);
            $eleveEvaluation->setBeginAt($today);
            $eleveEvaluation->setProgression(0);
            $eleveEvaluation->setEtat(0);
            foreach ($UneEvaluation as $key => $value){
              $eleveEvaluation->setTentativeRestante($value->getTentative());
              $heureActuelle = strtotime(date('H:i'));
              $duree=strtotime(date('H:i',$value->getDuree()));
              //dd($duree.'-'.$heureActuelle);
              $heureFin=$heureActuelle+$duree;

              $dureeRestante=$heureFin-$heureActuelle;

              $eleveEvaluation->setDureeRestant(date('H:i',$dureeRestante));

              //dd(date('H:i',$dureeRestante));
            }
            
            $this->em->persist($eleveEvaluation);

        }

        else if ($UneEleveEvaluation!=null) {
            foreach ($UneEleveEvaluation as $key => $value){
               $tentativeRestante=$value->getTentativeRestante();
               if ($tentativeRestante>=1) {

                $value->setRepriseAt($today);
                //$value->setEtat(0);
                $this->em->persist($value);

               }

               else if ($tentativeRestante==0) {

                 return $this->redirectToRoute('app.entrainer.repondre_quiz');

               }

            }
        }
        
       

        

        /* foreach ($UneEvaluation as $key => $value) {
           $tentativeRestante=$value->getTentative();
           if ($tentativeRestante==3) {
               $value->setTentative(2);
           }
           else if ($tentativeRestante==2) {
               $value->setTentative(1);
           }
           else if ($tentativeRestante==1) {
               $value->setTentative(0);
           }
           else if ($tentativeRestante==0) {
            return $this->redirectToRoute('app.entrainer.repondre_quiz');
           }
           
        }*/
        
        $this->em->flush();


        return $this->render('Eleve/utilities/wizards/vertical.html.twig', [
            'controller_name' => 'RepondreQuizController',
            'uneEvaluation'=> $UneEvaluation,
            "idEvaluation"=>$id
            
            
        ]);

    }

    #[Route('/entrainer/repondre_evaluation1-{id}', name: 'app.entrainer.repondre_evaluation1')]
    public function inserer(Security $security,Evaluation $evaluation,int $id,Request $request): Response
    {

        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            
            return $this->redirectToRoute('app.connexion');
            
        }
        else{
            if($this->authorizationChecker->isGranted('ROLE_PARENT')){

                return $this->redirectToRoute('apps.accueil');
                
            }
        }
    /*---------Queslques requestes de base (Recuperer une evaluation) et (Tout les sujets de cette evaluation) ainsi que le ( objet getUser() de eleve)----------*/

        $UneEvaluation = $this->repoEvaluation->UneEvaluation($id);
        $LesSujets = $this->repoSujet->LesSujets($id);
        $eleve = $this->getUser()->getEleve();
        $UneEleveEvaluation = $this->repoEleveEvaluation->UneEleveEvaluation($id,$eleve->getId());


    /*--------------------Création de nouvels objet--------------------------------*/
        $eleveEvaluation = new EleveEvaluation;
        
        
        $today = date('Y-m-d H:i');
        
    /*------------------------Récupérations des réponses--------------------------------*/
        global $iteration ;
        global $iteration1 ;
        global $iterations ;
        global $iterat ;
        global $iter ;
        global $etat1;
        global $etat2;
        global $nombre;
        global $note;
        global $tab;
        $iterations=0;
        $noteRecuSujet = 0;
            $laNote=0;
            $t=0;
            foreach ($LesSujets as $key => $value) {
                $iteration=0;
                $noteRecuSujet=0;
                $b=$key+1;
                $eleveSujet='eleveSujet'.$b;
                $eleveSujet = new EleveSujet;

                $sujetId = $request->request->get('idSujet'.$key+1);
                $idSujet = $value;
                if (isset($sujetId)) {
                    
                $eleveSujet->setEleve($eleve);
                $eleveSujet->setSujet($idSujet);
                $eleveSujet->setEvaluation($evaluation);

                    $LesPropositions = $this->repoProposition->LesPropositions($idSujet->getId());
                    $notation = $request->request->get('notation'.$key+1);
                    $nombrePropositions= $this->repoSujet->nombrePropositions($idSujet);
                    foreach ($nombrePropositions as $item => $val) {
                        $nombre=$val->getNombre();
                            
                    }

                    if ($nombre>=1) {
                        $notePar = $notation/$nombre;
                    }
            /*--------------Toutes les propositions d'un sujet-------------------*/
                    foreach ($LesPropositions as $keys => $values) {

                        $a=$keys+1;
                        $t=$t+1;
                        $eleveProposition='eleveProposition'.$a;
                        $eleveProposition = new EleveProposition;


                        

                        

                       
                       
                       // dd($reponsePropo);

                        if ($keys<4) {

                            $verificationReponse = $this->repoProposition->verificationReponse($values);

                            foreach ($verificationReponse as $keyy => $valuee) {

                                $iterat = $iterat+($a);
                                $resultat[$keys]='V'.$iterat;
                                $verification = $valuee->isReponseValide();
                               // dd($verification);
                                if ($value->getNombre()==1) {
                                   
                                   $reponsePropor = $request->request->get('reponser'.$b.$a);
                                   //$tab[$t]=$reponsePropor;

                                   if ($reponsePropor==null){
                                    
                                    $eleveProposition->setReponseEleve(0);
                                    $eleveProposition->setNoteRecu(0);
                                     //dd('oki');
                                    }

                                    

                                    elseif ($reponsePropor!=null){
                                         //dd('oki');
                                        $etat1 = true;
                                        $iter = $iter+1;
                                        if ($reponsePropor == $verification AND $verification==1) {
                                             //dd('oki');

                                            $eleveProposition->setNoteRecu($notePar);

                                            $noteRecuSujet = $noteRecuSujet + $notePar;

                                            $iteration = $iteration+1;


                                              
                                        }
                                        else{
                                             //dd('oki');
                                            $iterations = $iterations+1;
                                            
                                            $eleveProposition->setNoteRecu(0);
                                              
                                        }

                                        $eleveProposition->setReponseEleve($reponsePropor);
                                    }
                                }
                                elseif($value->getNombre()>=2){
                                    //dd('ok');
                                    $etat1 = true;
                                    $iter = $iter+1;
                                    $reponsePropo = $request->request->get('reponse'.$b.$a);
                                    //$tabr[$t]=$reponsePropo;

                                    if ($reponsePropo==null){
                                        $eleveProposition->setReponseEleve(0);
                                        $eleveProposition->setNoteRecu(0);
                                    }

                                    elseif ($reponsePropo!=null){
                                        
                                        if ($reponsePropo == $verification AND $verification==1) {
                                            $tabr[$a]=$verification;
                                            $eleveProposition->setNoteRecu($notePar);

                                            $noteRecuSujet = $noteRecuSujet + $notePar;

                                            $iteration = $iteration +1;
                                            //dd($iteration1);
                                             //$tab[$t]=$iteration;     
                                        }
                                        else{
                                            //$iterations = $iterations+1;
                                            
                                            $eleveProposition->setNoteRecu(0);
                                              
                                        }

                                        $eleveProposition->setReponseEleve($reponsePropo);
                                    }
                                }
                            }


                         
                            $eleveProposition->setEleve($eleve);
                            $eleveProposition->setSujet($value);
                            $eleveProposition->setPropositions($values);     

                        }
                            
                        

                        $this->em->persist($eleveProposition);
                        //$this->em->flush();

                        //dd('oki');
                    }



                    /*---------------------La réponse si le sujet est un questionnaire-------------------------*/
                   
                    if ($value->isTypeEvaluation()==1) {
                        $reponseQuest = $request->request->get('reponseQuest'.$b);
                        if (isset($reponseQuest) AND $reponseQuest!=null) {
                            $etat2 = true;
                            $eleveSujet->setEleve($eleve);
                            $eleveSujet->setReponse($reponseQuest);
                            $eleveSujet->setSujet($idSujet);
                        
                        }
                    }
                    //dd($iter);
                    /*---------------------Persistance de certains objet si le sujet est un questionnaire-------------------------*/
                    
                    if ($value->isTypeEvaluation()==1) {

                        if ($etat2==true) {
                            $eleveSujet->setEtat(1);
                            //$this->em->persist($eleveSujet);
                            $eleveSujet->setNoteRecu(0);
                            $eleveSujet->setEtatReponse(4);
                        }

                        else if($etat2!=true){
                            $eleveSujet->setEtat(0);
                            //$this->em->persist($eleveSujet);
                            $eleveSujet->setNoteRecu(0);
                            $eleveSujet->setEtatReponse(0);
                        }
                    }

                    /*---------------------Persistance de certains objet si le sujet est un Quiz-------------------------*/

                    else if ($value->isTypeEvaluation()==0) {
                        
                        if ($iteration==$nombre){
                            $eleveSujet->setEtatReponse(2);
                            //$this->em->persist($eleveSujet);
                        }
                        else if ($iteration==1 AND $nombre>=2) {
                            $eleveSujet->setEtatReponse(1);
                            //$this->em->persist($eleveSujet);
                        }
                        else if ($iteration==0 AND $nombre>=1) {
                            $eleveSujet->setEtatReponse(0);
                            //$this->em->persist($eleveSujet);
                        }

                        if ($etat1==true) {
                        $eleveSujet->setEtat(1);
                        //$this->em->persist($eleveSujet);
                        $eleveSujet->setNoteRecu($noteRecuSujet);
                        //dd('ok');
                        }
                        
                        else if ($etat1!=true){
                            $eleveSujet->setEtat(0);

                            //$this->em->persist($eleveSujet);
                            $eleveSujet->setNoteRecu(0);
                        }
                    }





                    $this->em->persist($eleveSujet);
                    //$this->em->flush();

                    
                }
                //dd($noteRecuSujet);

                $note=$note+$noteRecuSujet;
                
                
            }
            //dd($note);
            //dd($iter);
           // dd($tab);
            $d=0;
            global $sur201;
            global $noteFinale;
            for ($i=0; $i <21 ; $i+=0.1) { 
                $noteSur=$evaluation->getNoteSur();
                $sur20=$noteSur*$i;
                //dd($sur20);
                $d=$d+1;
                if ($sur20>=19 AND $sur20<=20) {
                    $sur201=$note*$i;
                    $noteFinale=ceil(abs($note*$i));
                    //dd($noteFinale);
                    if($evaluation->getNiveauEvaluation()==2){
                        $noteFinale=$noteFinale*2;
                        //dd($noteFinale);
                    }
                    elseif($evaluation->getNiveauEvaluation()==3){
                        $noteFinale=$noteFinale*3;
                    }
                }
               $tab[$d]=$i;
                
            }

            //dd($noteFinale);
        

        foreach ($UneEleveEvaluation as $key => $value) {
           $tentativeRestante=$value->getTentativeRestante();
           if ($tentativeRestante==3) {
               $value->setTentativeRestante(2);
           }
           else if ($tentativeRestante==2) {
               $value->setTentativeRestante(1);
           }
           else if ($tentativeRestante==1) {
               $value->setTentativeRestante(0);
           }

           $value->setEtat(1);
           $value->setSur20($sur201);
          // dd($noteFinale);
           $value->setNote($noteFinale);
           $value->setNoteInitiale($note);
           $endAt=$value->setEndAt($today);

           if ($value->getRepriseAt()==null) {
               $beginAt=strtotime($value->getBeginAt());
               $endAt=strtotime($today);

               $tempsMi=(int)ceil(abs($endAt-$beginAt));
               $tempsMis=date('H:i',$tempsMi);
               $value->setTempsMis($tempsMis);
           }

           else if ($value->getRepriseAt()!=null) {
               
               $repriseAt=strtotime($value->getRepriseAt());
               $endAt=strtotime($today);
               $tempsMisAvant=strtotime($value->getTempsMis());
               $tempsMi=(int)ceil(abs($endAt-$repriseAt));
               $temps=$tempsMi+$tempsMisAvant;
               $tempsMis=date('H:i',$temps);
               $value->setTempsMis($tempsMis);
           }
          
           $this->em->persist($value);
           
           
        }

        $this->em->flush();

        return $this->redirectToRoute('app.entrainer.repondre_quiz');

    }

    #[Route('/showEvaluation-{id}', name: 'app.showEvaluation')]
    public function showEvaluation(Security $security,Request $request,Evaluation $evaluation,int $id): Response
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
        $UneEleveEvaluation = $this->repoEleveEvaluation->UneEleveEvaluation($id,$eleve->getId());
        
        //$MesEleves = $this->repoEleve->MesEleves($parent->getId());
        $UneEvaluation = $this->repoEvaluation->UneEvaluation($id);
        //dd($UneEleveEvaluation).
        //dd($UneEleveEvaluation);
        $PropositionSujet = $this->repoEleveProposition->PropositionSujet($eleve->getId());
        $LesSujets = $this->repoSujet->LesSujets($id);
        $EvaluationSujets = $this->repoEleveSujet->EvaluationSujets($LesSujets);
        
        //dd($EvaluationSujets);
        $nombreRepondu=0;
        $nombreTrouver=0;
        foreach ($LesSujets as $key => $value){
            $CountReponduSujets = $this->repoEleveSujet->CountReponduSujets($value->getId());
            $nombreRepondu=$nombreRepondu+$CountReponduSujets;
        }

        foreach ($LesSujets as $key => $value){
            $CountTrouverSujets = $this->repoEleveSujet->CountTrouverSujets($value->getId());
            $nombreTrouver=$nombreTrouver+$CountTrouverSujets;
        }

        $CountSujets = $this->repoSujet->CountSujets();
        $CountAllSujets = $this->repoSujet->CountAllSujets($id);
        $CountReponduSujets = $nombreRepondu;
        $CountTrouverSujets = $nombreTrouver;

        foreach ($CountSujets as $key => $value){
            $CountPropositions= $this->repoProposition->CountPropositions($value->getId());
            //dd($CountPropositions);
            $value->setNombre($CountPropositions);
            $this->em->persist($value);
        }
        $this->em->flush();
        
        //dd($LesSujets);
        //dd($UneEvaluation);
        return $this->render('Eleve/apps/ecommerce/catalog/evaluation.html.twig', [
            'controller_name' => 'CreationQuestionnaireController',
            //'mesEleves'=>$MesEleves,
            'uneEleveEvaluation'=>$UneEleveEvaluation,
            'CountAllSujets'=>$CountAllSujets,
            'CountReponduSujets'=>$CountReponduSujets,
            'CountTrouverSujets'=>$CountTrouverSujets,
            'propositionSujet'=>$PropositionSujet,

            
        ]);

    }
}
