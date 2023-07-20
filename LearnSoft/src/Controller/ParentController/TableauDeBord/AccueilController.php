<?php

namespace App\Controller\ParentController\TableauDeBord;

use App\Entity\Parents;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Repository\EleveRepository;
use App\Repository\DeconnexionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
class AccueilController extends AbstractController
{
     public function __construct(AuthorizationCheckerInterface $authorizationChecker,Security $security, EleveRepository $repoEleve,DeconnexionRepository $repoDeconnexion, EntityManagerInterface $em)
    {
      
         $this->security = $security;
         $this->repoEleve = $repoEleve;
         $this->repoDeconnexion = $repoDeconnexion;
         $this->em = $em;
         $this->authorizationChecker = $authorizationChecker;

    }
    #[Route('/parent', name: 'apps.accueil')]
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
          
        return $this->render('Parent/indexParent.html.twig', [
            'controller_name' => 'AccueilController',
            'parent'=>$parent,
            'mesEleves'=>$MesEleves,
            'LoginParent'=>$LoginParent
        ]);
    }

}
