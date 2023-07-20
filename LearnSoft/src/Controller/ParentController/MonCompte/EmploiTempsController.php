<?php

namespace App\Controller\ParentController\MonCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\EmploiTemps;
use App\Entity\Eleve;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EmploiTempsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use App\Repository\DeconnexionRepository;
use App\Repository\EleveRepository;

class EmploiTempsController extends AbstractController
{

     public function __construct(EmploiTempsRepository $repoEmploiTemps, EntityManagerInterface $em,UtilisateurRepository $repoUtilisateur,DeconnexionRepository $repoDeconnexion, EleveRepository $repoEleve)
    {
        $this->repoEmploiTemps = $repoEmploiTemps;
        $this->repoUtilisateur = $repoUtilisateur;
        $this->repoDeconnexion = $repoDeconnexion;
        $this->em = $em;
        $this->repoEleve = $repoEleve;

    }
    #[Route('/parent/compteEmploiTemps', name: 'apps.compte.emploi-temps')]
    public function index(Request $request): Response
    {

        $parent = $this->getUser()->getParent();

        $emploiTemp = new EmploiTemps;

        $idParent = $this->getUser()->getParent();
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
        $selectEleve=$this->repoUtilisateur->selectEleve($idParent);
         foreach ($selectEleve as $item => $value) {
                        $classe=$value->getClasse();
        }
        
       // dd($selectEleve);

            $titre=$request->request->get('calendar_event_name');
            $description=$request->request->get('calendar_event_description');
            $jour=$request->request->get('calendar_event_location');
            $heureDebut=$request->request->get('calendar_event_start_time');
            $heureFin=$request->request->get('calendar_event_end_time');
            $classe=$request->request->get('classe');
            
            if(isset($titre,$description,$jour,$heureDebut,$heureFin,$classe)){
                //dd($titre."-".$description."-".$jour."-".$heureDebut."-".$heureFin);
                if ($heureFin>$heureDebut) {
                    //dd('true');
                   

                    foreach ($selectEleve as $item => $value) {
                        $classe=$value->getClasse();
                    }

                    dump($classe);

                   $emploiTemp->setTitreEvent($titre);
                   $emploiTemp->setDescription($description);
                   $emploiTemp->setJour($jour);
                   $emploiTemp->setHeureDebut($heureDebut);
                   $emploiTemp->setHeureFin($heureFin);
                   $emploiTemp->setParent($parent);
                   $emploiTemp->setClasse(1);
                   $this->em->persist($emploiTemp);
                   $this->em->flush();
                   return $this->redirectToRoute('apps.compte.emploi-temps');
                }

                elseif ($heureFin<$heureDebut || $heureFin==$heureDebut){
                    dd('false');
                }

                   
               
            }

            else{


                return $this->render('Parent/account/calendrier.html.twig', [
                    'controller_name' => 'EmploiTempsController',
                    'parent'=>$parent,
                    'LoginParent'=>$LoginParent,
                     'mesEleves'=>$MesEleves,
                ]);
            }

        return $this->render('Parent/account/calendrier.html.twig', [
            'controller_name' => 'EmploiTempsController',
            'parent'=>$parent,
            'LoginParent'=>$LoginParent,
             'mesEleves'=>$MesEleves,
        ]);
    }
}
