<?php

namespace App\Controller\ParentController\MonCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DeconnexionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EleveRepository;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

class ApercuController extends AbstractController
{
    public function __construct( AuthorizationCheckerInterface $authorizationChecker,EntityManagerInterface $em,DeconnexionRepository $repoDeconnexion, EleveRepository $repoEleve)
    {
        
        $this->repoDeconnexion = $repoDeconnexion;
        $this->em = $em;
        $this->repoEleve = $repoEleve;
        $this->authorizationChecker = $authorizationChecker;
        

    }
    #[Route('/parent/compteApercu', name: 'apps.compte.apercu')]
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
        $LoginParent = $this->repoDeconnexion->LoginParent($parent);
        $MesEleves = $this->repoEleve->MesEleves($parent->getId());
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
        return $this->render('Parent/account/apercu.html.twig', [
            'controller_name' => 'ApercuController',
            'parent'=>$parent,
             'mesEleves'=>$MesEleves,
            'LoginParent'=>$LoginParent
        ]);
    }
}
