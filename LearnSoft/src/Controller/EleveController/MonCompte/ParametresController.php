<?php

namespace App\Controller\EleveController\MonCompte;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ParametresController extends AbstractController
{
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
      
      
         $this->authorizationChecker = $authorizationChecker;
         

    }
    #[Route('/compte/parametres', name: 'app.compte.parametres')]
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
        return $this->render('Eleve/account/parametres.html.twig', [
            'controller_name' => 'ParametresController',
        ]);
    }
}
