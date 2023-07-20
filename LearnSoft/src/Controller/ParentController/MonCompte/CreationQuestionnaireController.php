<?php

namespace App\Controller\ParentController\MonCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Parents;
use App\Entity\Evaluation;
use App\Entity\Sujet;
use App\Entity\Eleve;
use App\Entity\Propositions;
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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DeconnexionRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class CreationQuestionnaireController extends AbstractController
{
    public function __construct(AuthorizationCheckerInterface $authorizationChecker, EntityManagerInterface $em,EleveRepository $repoEleve,EvaluationRepository $repoEvaluation,SujetRepository $repoSujet,PropositionsRepository $repoProposition,MatiereRepository $repoMatiere,ClasseRepository $repoClasse,UtilisateurRepository $repoUtilisateur,EleveEvaluationRepository $repoEleveEvaluation,EleveSujetRepository $repoEleveSujet,ElevePropositionRepository $repoEleveProposition,DeconnexionRepository $repoDeconnexion)
    {
      
         $this->repoEleve = $repoEleve;
         $this->repoDeconnexion = $repoDeconnexion;
         $this->repoEleveSujet = $repoEleveSujet;
         $this->repoEleveProposition = $repoEleveProposition;
         $this->repoUtilisateur = $repoUtilisateur;
         $this->repoEleveEvaluation = $repoEleveEvaluation;
         $this->repoEvaluation = $repoEvaluation;
         $this->repoSujet = $repoSujet;
         $this->repoProposition = $repoProposition;
         $this->authorizationChecker = $authorizationChecker;
         $this->repoMatiere = $repoMatiere;
         $this->repoClasse = $repoClasse;
         $this->em = $em;

    }


    #[Route('/parent/compteCreationQuestionnaire', name: 'apps.compte.creation-quest')]
    public function index(Security $security,Request $request): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            
            return $this->redirectToRoute('app.connexion');
            
        }
        else{
            if($this->authorizationChecker->isGranted('ROLE_ELEVE')){

                return $this->redirectToRoute('app.connexion');
                
            }
        }
    /*----------------Requetes SQL et recupérations */
        $parent = $this->getUser()->getParent();
        //dd($parent->getUtilisateurs());
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
        $LesEvaluations = $this->repoEvaluation->LesEvaluations($parent->getId());
        $LesMatieres = $this->repoMatiere->LesMatieres();
        $LesClasses = $this->repoClasse->LesClasses();
        foreach($LesEvaluations as $item=>$value){
            $DD=strtotime($value->getDateDebut());
            $DF=strtotime($value->getDateFin());
            $HD=strtotime($value->getHeureDebut());
            $HF=strtotime($value->getHeureFin());

            $aujoudhui=date('Y-m-d');
            $heure=date('H:i');
            $aHeure=strtotime($heure);
           
            $aJour=strtotime($aujoudhui);

            $TT=(int)ceil(abs($DD-$aJour)/86400);


            if ($TT==0 AND $heure<$value->getHeureDebut()) {
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

            if ($aujoudhui>=$value->getDateDebut() AND $heure>=$value->getHeureDebut()) {
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

        //$this->em->flush();

        if ($LesEvaluations==null) {
            $TT=0;
        }

/*---------------------------------Création d'une Evaluation-------------------------------------------*/
    /*--------Création de l'Obejet Evaluation--------------*/

        $evaluation = new Evaluation;

    /*--------Récupérations des requetes EVALUATION --------------*/

        $titre = $request->request->get('titre');
        $description = $request->request->get('description');
        $classe = $request->request->get('classe');
        $noteSur = 20;
        $niveau = $request->request->get('niveau');
        $matiere = (int)$request->request->get('matiere');

    /*--------Récupérations des requetes HONORAIRES --------------*/  

        $heureDebut = $request->request->get('calendar_event_start_time');
        $heureFin = $request->request->get('calendar_event_end_time');
        $dateDebut = $request->request->get('calendar_event_start_date');
        $dateFin = $request->request->get('calendar_event_end_date');
        $tentative = $request->request->get('tentative');
        $duree = $request->request->get('duree');

    /*--------Vérification des valeurs récupérées et persistance des objets--------------*/

        /*-----------------Persistances Evaluation-----------------*/

        $ctrlHeure=strtotime($heureFin)-strtotime($heureDebut);

        //dd($heureDebut.'---'.$heureFin.'---'.$dateDebut.'---'.$dateFin.'---'.$ctrlHeure.'---'.$duree);
        if ($request->isXmlHttpRequest()) {
            
            if ($titre==null OR $description==null OR $classe==null OR $matiere==null OR $noteSur==null OR $heureDebut==null OR $heureFin==null OR $dateDebut==null OR $dateFin==null OR $tentative==null OR $duree==null){
                    
                $message="Tout les champs de 'Détails' et 'Honoraires' sont requis";
                $titre = "Erreur sur la partie 'Détails' et 'Honoraires' ";
                $tableauData= array();
                $tableauData[]= $titre;
                $tableauData[]= $message;
            
                return $this->json($tableauData);

            }

                $dateDebutStr = strtotime($dateDebut); // convertir la dateDebut en dateDebut Unix
                $dateFinStr = strtotime($dateFin); // convertir la dateDebut en dateDebut Unix
                $heureDebutStr = strtotime($heureDebut); // convertir la dateDebut en dateDebut Unix
                $heureFinStr = strtotime($heureFin); // convertir la dateDebut en dateDebut Unix
                $aujourdhui =strtotime(Date('Y-m-d')); // dateDebut Unix de la dateDebut et heureDebut actuelles
                $heure = time();

                if ($dateDebutStr == $dateFinStr AND $dateDebutStr>=$aujourdhui AND $dateFinStr>=$aujourdhui ) {
                    if ($heureDebutStr < $heure OR  $heureFinStr<=$heure) {
                        $message="Les heures ne doivent pas être inférieur à l'heure actuelle";
                        $titre = "Erreur sur la partie 'Honoraires' ";
                        $tableauData= array();
                        $tableauData[]= $titre;
                        $tableauData[]= $message;
                        return $this->json($tableauData);

                }
                    if ($heureDebutStr >= $heureFinStr) {
                        $message="L'heure de début ne doivent pas être inférieur ou supérieur a celle de fin et vice versa";
                        $titre = "Erreur sur la partie 'Honoraires' ";
                        $tableauData= array();
                        $tableauData[]= $titre;
                        $tableauData[]= $message;
                        return $this->json($tableauData);
                    }
                }

                if ($dateDebutStr < $aujourdhui OR $dateFinStr<$aujourdhui) {
                    $message="Veuillez revoir les dates de début et de fin";
                    $titre = "Erreur sur la partie 'Honoraires' ";
                        $tableauData= array();
                        $tableauData[]= $titre;
                        $tableauData[]= $message;
                        return $this->json($tableauData);
                }

                

                if ($dateDebutStr > $dateFinStr) {
            
                    $message="La date de début ne doivent pas être inférieur ou superieur a celle de fin et vice versa";
                    $titre = "Erreur sur la partie 'Honoraires' ";
                        $tableauData= array();
                        $tableauData[]= $titre;
                        $tableauData[]= $message;
                        return $this->json($tableauData);
                
                }


            if (isset($titre,$description,$classe,$matiere,$noteSur,$heureDebut,$heureFin,$dateDebut,$dateFin,$tentative,$duree)) {
                
                $aujoudhui=date('Y-m-d');

                
                
                foreach($LesMatieres as $item=>$value){
                    if ($matiere==$value->getId()) {
                        
                        $objetMatiere=$value;

                    }
                }

                foreach($LesClasses as $item=>$value){
                    if ($classe==$value->getId()) {
                        
                        $objetClasse=$value;

                    }
                }
                //dd($objetMatiere);
                $evaluation->setTitre($titre);
                $evaluation->setDescription($description);
                $evaluation->setClasse($objetClasse);
                $evaluation->setHeureDebut($heureDebut);
                $evaluation->setHeureFin($heureFin);
                $evaluation->setDateDebut($dateDebut);
                $evaluation->setDateFin($dateFin);
                $evaluation->setTentative($tentative);
                $evaluation->setDuree($duree);
                //$evaluation->setNoteSur($noteSur);
                $evaluation->setStatut(0);
                $evaluation->setEtat("Non débuté");
                $evaluation->setNiveauEvaluation($niveau);
                $evaluation->setParents($parent);
                $evaluation->setMatiere($objetMatiere);
                $evaluation->setOverdue(0);
            
                $this->em->persist($evaluation);
                // dd($matiere);
            
                /*--------Récupérations des requetes SUJETS & PROPOSITIONS --------------*/
                $laNote=0;
                $igg=0;
                $ig=0;
                $igg1=0;
                $ig1=0;
                

                for ($i=1; $i <6 ; $i++) { 

                    $sujetVar='sujet'.$i;
                    $sujetVar = new Sujet;

                        $question = $request->request->get('question'.$i);


                        $na = (int)$request->request->get('na'.$i);
                        $nb = (int)$request->request->get('nb'.$i);
                        $nc = (int)$request->request->get('nc'.$i);
                        $nd = (int)$request->request->get('nd'.$i);
                        $ne = (int)$request->request->get('ne'.$i);

                    
                    /*-----------------Persistances Sujet-----------------*/
                    if ($question==null) {
            
                        $message="Le champ 'Question est requis' ";
                        $titre = "Erreur sur le 'Sujet'".$i;
                            $tableauData= array();
                            $tableauData[]= $titre;
                            $tableauData[]= $message;
                            return $this->json($tableauData);
                    
                    }
                     if (($na==null AND $nb==null AND $nc==null AND $nd==null AND $ne==null)){
                        
                        $message="Mettez un barème a cette question.";
                        $titre = "Erreur sur le 'Sujet'".$i;
                            $tableauData= array();
                            $tableauData[]= $titre;
                            $tableauData[]= $message;
                            return $this->json($tableauData);
                     }

                    if (($na!=null OR $nb!=null OR $nc!=null OR $nd!=null OR $ne!=null)){
                        
                        $sujetVar->setQuestion($question);
                        if ($na!=null) {
                            $sujetVar->setNotation($na);
                            $laNote=$laNote+$na;
                        }
                        if ($nb!=null) {
                            $sujetVar->setNotation($nb);
                            $laNote=$laNote+$nb;
                        }
                        if ($nc!=null) {
                            $sujetVar->setNotation($nc);
                            $laNote=$laNote+$nc;
                        }
                        if ($nd!=null) {
                            $sujetVar->setNotation($nd);
                            $laNote=$laNote+$nd;
                        }
                        if ($ne!=null) {
                            $sujetVar->setNotation($ne);
                            $laNote=$laNote+$ne;
                        }

                        $sujetVar->setEtat(0);
                        $sujetVar->setEvaluation($evaluation);

                        
                $iteration1 =0;
                $iteration2=0;
                $iteration3=0;
                $iteration4=0;
                $iterationnn=0;
                $noProposition = false;
                        /*-----------------Persistances Propositions-----------------*/
                        for ($j=1; $j<5; $j++) { 

                            $propositionVar='proposition'.$j;
                            $propositionVar = new Propositions;

                            $proposition = $request->request->get('proposition'.$j.'-'.$i);
                            $reponse = $request->request->get('reponse'.$j.'-'.$i);
                            //dd($reponse);
                            if ($proposition==null  AND $reponse==null) {
                            $iteration1=$iteration1+1;
                                if ($iteration1==4) {
                                    $noProposition = true;
                                    $sujetVar->setTypeEvaluation(1);
                                    }
                            }
                            elseif ($proposition!=null AND $reponse==null) {
                            $iteration2=$iteration2+1;
                               if ($iteration2 ==2 AND $iterationnn<2) {
                                $message="Choissisez une ou plusieurs bonne réponse pour les propositions";
                                $titre = "Erreur sur le 'Sujet'".$i;
                                    $tableauData= array();
                                    $tableauData[]= $titre;
                                    $tableauData[]= $message;
                                    return $this->json($tableauData);
                                }
                               
                                
                            }
                            if ($proposition!=null) {
                                $iterationnn=$iterationnn+1;
                            }
                            
                                if ($iterationnn<2 AND $j>=4 AND $noProposition != true) {
                                    $message=$iteration1;
                                    $titre = "Erreur sur le 'Sujet'".$i;
                                        $tableauData= array();
                                        $tableauData[]= $titre;
                                        $tableauData[]= $message;
                                        return $this->json($tableauData);
                                    }
                                    
                                
                            if ($proposition==null  AND $reponse!=null) {
                            
                                $message="Vous ne pouvez pas choisir une réponse pour une proposition inexistante";
                                $titre = "Erreur sur le 'Sujet'".$i;
                                    $tableauData= array();
                                    $tableauData[]= $titre;
                                    $tableauData[]= $message;
                                    return $this->json($tableauData);
                                
                            }
                            if (($na==null AND $nb==null AND $nc==null AND $nd==null AND $ne==null)){
                        
                                $message="Mettez un barème a cette question.";
                                $titre = "Erreur sur le 'Sujet'".$i;
                                    $tableauData= array();
                                    $tableauData[]= $titre;
                                    $tableauData[]= $message;
                                    return $this->json($tableauData);
                             }

                            
                             /*elseif ($proposition!=null AND $reponse!=null) {
                                $iteration2=$iteration2+1;
                                if ($iteration2==4) {
                                    $sujetVar->setTypeEvaluation(0);
                                    
                                }*/
                                
                            
                            


                            $d=$j;
                        
                        }

                        for ($j=1; $j<5; $j++) { 

                            $propositionVar='proposition'.$j;
                            $propositionVar = new Propositions;

                            $proposition = $request->request->get('proposition'.$j.'-'.$i);
                            $reponse = $request->request->get('reponse'.$j.'-'.$i);
                            //dump($reponse);
                            if($proposition!=null){
                                if($reponse!=null){
                                    $iteration4=$iteration4+1;
                                    if ($iteration4<1) {
                                    $message="Choisissez une ou plusieurs réponse";
                                    $titre = "Erreur sur le 'Sujet'".$i;
                                    $tableauData= array();
                                    $tableauData[]= $titre;
                                    $tableauData[]= $message;
                                    return $this->json($tableauData);
                                    }
                                    if ($iteration4>=1) {
                                    $sujetVar->setTypeEvaluation(0);
                                    }

                                }
                            
                            
                            }


                            $d=$j;
                        
                        }
                        

                    
                        
                        $this->em->persist($sujetVar);

                        /*-----------------Persistances Propositions-----------------*/
                        for ($j=1; $j <5; $j++) { 

                            $propositionVar='proposition'.$j;
                            $propositionVar = new Propositions;

                            $proposition = $request->request->get('proposition'.$j.'-'.$i);
                            $reponse = $request->request->get('reponse'.$j.'-'.$i);


                            if (isset($proposition) AND ($reponse!=null OR $reponse==null) AND $proposition!=null) {
                                
                                $propositionVar->setLibelle($proposition);
                                if ($reponse!=null) {
                                $propositionVar->setReponseValide($reponse);
                                }

                                if ($reponse==null) {
                                $propositionVar->setReponseValide(0);
                                }

                                $propositionVar->setSujet($sujetVar);

                                $this->em->persist($propositionVar);
                            }

                        $tabr[$i]=$sujetVar;
                            
                        }
                    }
                    
                }
            // dd($iteration4);
                    //dd($sujetVar);
            //dd($tabr);
                $UneEvaluation = $this->repoEvaluation->UneEvaluation($evaluation->getId());
                if ($laNote<10) {
                    $message="Revoyez vos bareme, le total doit faire 10";
                    $titre = "Erreur sur les Barèmes";
                    $tableauData= array();
                    $tableauData[]= $titre;
                    $tableauData[]= $message;
                    return $this->json($tableauData);
                    }
                foreach ($evaluation as $key =>$value) {
                    //dd($evaluation);
                    $evaluation->setNoteSur($laNote);
                    $this->em->persist($evaluation);
                }

                    $this->em->flush();
                    return new JsonResponse([
                        'success' => true,
                        'redirect_url' => $this->generateUrl('apps.compte.creation-quest'),
                    ]);
                    
            }
            else{
                return $this->render('Parent/account/creation-quest.html.twig', [
                    'controller_name' => 'CreationQuestionnaireController',
                    'parent'=>$parent,
                    'mesEleves'=>$MesEleves,
                    'lesEvaluations'=>$LesEvaluations,
                    'today'=>$TT,
                    'lesMatieres'=>$LesMatieres,
                    'lesClasses'=>$LesClasses,
                    'LoginParent'=>$LoginParent
                ]);
            }

            
            
        }else{
            return $this->render('Parent/account/creation-quest.html.twig', [
                'controller_name' => 'CreationQuestionnaireController',
                'parent'=>$parent,
                'mesEleves'=>$MesEleves,
                'lesEvaluations'=>$LesEvaluations,
                'today'=>$TT,
                'lesMatieres'=>$LesMatieres,
                'lesClasses'=>$LesClasses,
                'LoginParent'=>$LoginParent
            ]);
        }
    }

    #[Route('/parent/evaluation-{id}', name: 'apps.evaluation')]
    public function showEvaluation(Security $security,Request $request,Evaluation $evaluation,int $id): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            
            return $this->redirectToRoute('app.connexion');
            
        }
        else{
            if($this->authorizationChecker->isGranted('ROLE_ELEVE')){

                return $this->redirectToRoute('app.connexion');
                
            }
        }
        //dd($evaluation->getClasse());

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
        $UneEvaluation = $this->repoEvaluation->UneEvaluation($id);
        $LesSujets = $this->repoSujet->LesSujets($id);
        $SelectClasseEvaluation = $this->repoUtilisateur->SelectClasseEvaluation($evaluation->getClasse());

        $SelectEvaluation = $this->repoEleveEvaluation->SelectEvaluation($id);


        $CountSujets = $this->repoSujet->CountSujets();

        foreach ($CountSujets as $key => $value) {
            $CountPropositions= $this->repoProposition->CountPropositions($value->getId());
            //dd($CountPropositions);
            $value->setNombre($CountPropositions);
            $this->em->persist($value);

        }
        $this->em->flush();
        
        //dd($LesSujets);
        //dd($UneEvaluation);
        return $this->render('Parent/apps/ecommerce/catalog/edit-product.html.twig', [
            'controller_name' => 'CreationQuestionnaireController',
            'parent'=>$parent,
            'mesEleves'=>$MesEleves,
            'uneEvaluation'=>$UneEvaluation,
            'selectClasseEvaluation'=>$SelectClasseEvaluation,
            'selectEvaluation'=>$SelectEvaluation,
            'LoginParent'=>$LoginParent

            
        ]);

    }

    #[Route('/parent/correction-{id}-{idEvaluation}', name: 'apps.correction')]
    public function correctionEvaluation(Security $security,Request $request,int $id,int $idEvaluation): Response
    {
        //dd($evaluation->getClasse());
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
        $UneEvaluation = $this->repoEvaluation->UneEvaluation($idEvaluation);
        $LesSujets = $this->repoSujet->LesSujets($idEvaluation);
        //$SelectClasseEvaluation = $this->repoUtilisateur->SelectClasseEvaluation($evaluation->getClasse());
        $SujetsId = $this->repoSujet->SujetsId($idEvaluation);
        //dd($SujetsId);

        $SelectEvaluation = $this->repoEleveEvaluation->SelectEvaluation($id);
        $SujetEvaluation = $this->repoEleveSujet->SujetEvaluation($idEvaluation,$id);

        $PropositionSujet = $this->repoEleveProposition->PropositionSujet($id);

        //dd($SujetEvaluation);

        $CountSujets = $this->repoSujet->CountSujets();

        foreach ($CountSujets as $key => $value) {
            $CountPropositions= $this->repoProposition->CountPropositions($value->getId());
            //dd($CountPropositions);
            $value->setNombre($CountPropositions);
            $this->em->persist($value);

        }
        $this->em->flush();


        //dd($LesSujets);
        //dd($UneEvaluation);
        return $this->render('Parent/apps/ecommerce/catalog/correction.html.twig', [
            'controller_name' => 'CreationQuestionnaireController',
            'parent'=>$parent,
            'mesEleves'=>$MesEleves,
            'uneEvaluation'=>$UneEvaluation,
            'sujetEvaluation'=>$SujetEvaluation,
            'selectEvaluation'=>$SelectEvaluation,
            'propositionSujet'=>$PropositionSujet,
            'idEleve'=>$id,
            'LoginParent'=>$LoginParent


            
        ]);

    }

    #[Route('/parent/insererCorrection-{id}-{idEvaluation}', name: 'apps.insererCorrection')]
    public function insererCorrectionEvaluation(Security $security,Request $request,int $id,int $idEvaluation): Response
    {
        //dd($evaluation->getClasse());
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

        $UneEvaluation = $this->repoEvaluation->UneEvaluation($idEvaluation);
        $LesSujets = $this->repoSujet->LesSujets($idEvaluation);
        
        $SujetsId = $this->repoSujet->SujetsId($idEvaluation);
        
        $SelectEvaluation = $this->repoEleveEvaluation->SelectEvaluation($id);
        $SujetEvaluation = $this->repoEleveSujet->SujetEvaluation($idEvaluation,$id);

        $PropositionSujet = $this->repoEleveProposition->PropositionSujet($id);

        $CountSujets = $this->repoSujet->CountSujets();

        /*foreach ($CountSujets as $key => $value) {
            $CountPropositions= $this->repoProposition->CountPropositions($value->getId());
            //dd($CountPropositions);
            $value->setNombre($CountPropositions);
            $this->em->persist($value);

        }
        $this->em->flush();*/



        /*================================================================================================================================================*/

        /*---------Queslques requestes de base (Recuperer une evaluation) et (Tout les sujets de cette evaluation) ainsi que le ( objet getUser() de eleve)----------*/

       
        $UneEleveEvaluation = $this->repoEleveEvaluation->UneEleveEvaluation($idEvaluation,$id);
        $SujetEvaluation = $this->repoEleveSujet->SujetEvaluation($idEvaluation,$id);

    /*--------------------Création de nouvels objet--------------------------------*/
        
        
        
        $today = date('Y-m-d H:i');
        
    /*------------------------Récupérations des réponses--------------------------------*/
        global $iteration ;
        global $iterations ;
        global $iterat ;
        global $etat1;
        global $etat2;
        global $nombre;
        global $note;
        $iterations=0;
        $noteRecuSujet = 0;

            $laNote=0;
        
            foreach ($SujetEvaluation as $key => $value) {
                
                $b=$key+1;
                
                $na = $request->request->get('na'.$key+1);
                $nb = $request->request->get('nb'.$key+1);
                $nc = $request->request->get('nc'.$key+1);

                $notation = $request->request->get('notation'.$key+1);
                $typeEvaluation = $request->request->get('typeEvaluation'.$key+1);
                //$nc = $request->request->get('nc'.$key+1);

                if ($na!=null or $nb!=null or $nc!=null) {
                    
                    
                    /*---------------------La réponse si le sujet est un questionnaire-------------------------*/
                   
                    if ($typeEvaluation == 1) {

                        $apport = $request->request->get('apport'.$key+1);
                        if (isset($apport,$notation) AND $apport!=null){
                            $value->setApport($apport);
                        }

                        if($na!=null){
                            $laNote=$laNote+$na;

                            $value->setEtatReponse($na);
                            $value->setNoteRecu(0);
                            //dd($laNote);
                        }
                        if($nb!=null){
                            $laNote=$laNote + ($notation/2);
                            $value->setEtatReponse($nb);
                            $value->setNoteRecu($notation/2);
                            //dd($laNote);
                            //dd($laNote);
                        }
                        if($nc!=null){
                            $laNote=$laNote+$notation;
                            $value->setEtatReponse($nc);
                            $value->setNoteRecu($notation);
                           
                        }
                    }

                    
                }



                $this->em->persist($value);
                
                
            }

        $ii=0;
            $d=0;
        global $sur201;
        global $noteFinal;
        foreach ($UneEvaluation as $key => $value) {
            // code...
        
            for ($i=0; $i <21 ; $i+=0.1){ 
                $noteSur=$value->getNoteSur();
                $sur20=$noteSur*$i;
                $d=$i;
                if ($sur20>=19 AND $sur20<=20) {
                    //$sur201=$laNote*$i;
                    $noteFinal=$laNote*$i;
                    //dd($noteFinale);
                    if($value->getNiveauEvaluation()==2){
                        $noteFinal=$noteFinal*2;
                        //dd($noteFinale);
                    }
                    elseif($value->getNiveauEvaluation()==3){
                        $noteFinal=$noteFinal*3;
                        //dd($noteFinale);
                    }
                }
               //$tab[$d]=$i;
                
            }

        }
        //dd($laNote);
        foreach ($UneEleveEvaluation as $key => $value) {
           
           $noteFinale=$noteFinal+($value->getNote());
           $noteInitiale=$laNote+($value->getNoteInitiale());
           //dd($noteFinale);
           $sur201=($laNote+$value->getSur20());
           //dd($sur201);
           $value->setCorrection(1);
           $value->setSur20($sur201);
           $value->setNote($noteFinale);
           $value->setNoteInitiale($noteInitiale);
           $this->em->persist($value);
          

           
        }
        $this->em->flush();
        
        
        
        return $this->redirectToRoute('apps.evaluation',['id' => $idEvaluation]);

    }

    #[Route('/parent/upd-{id}', name: 'apps.update')]
    public function upEvaluation(Request $request,Evaluation $evaluation,int $id): Response
    {
        //dd($evaluation->getClasse());

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
        $UneEvaluation = $this->repoEvaluation->UneEvaluation($id);
        $LesSujets = $this->repoSujet->LesSujets($id);
        $SelectClasseEvaluation = $this->repoUtilisateur->SelectClasseEvaluation($evaluation->getClasse());

        $SelectEvaluation = $this->repoEleveEvaluation->SelectEvaluation($id);


        $CountSujets = $this->repoSujet->CountSujets();

        foreach ($CountSujets as $key => $value) {
            $CountPropositions= $this->repoProposition->CountPropositions($value->getId());
            //dd($CountPropositions);
            $value->setNombre($CountPropositions);
            $this->em->persist($value);

        }
        $this->em->flush();
        
        //dd($LesSujets);
        //dd($UneEvaluation);
        return $this->render('Parent/account/correctionModif.html.twig', [
            'controller_name' => 'CreationQuestionnaireController',
            'parent'=>$parent,
            'mesEleves'=>$MesEleves,
            'uneEvaluation'=>$UneEvaluation,
            'selectClasseEvaluation'=>$SelectClasseEvaluation,
            'selectEvaluation'=>$SelectEvaluation,
            'LoginParent'=>$LoginParent

            
        ]);

    }
}
