<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;
use App\Entity\Deconnexion;
use Doctrine\ORM\EntityManagerInterface;
class SecurityController extends AbstractController
{

    public function __construct(EntityManagerInterface $em)
    {
      
        
         $this->em = $em;

    }
   
    #[Route('/connexion', name: 'app.connexion')]
    public function index(AuthenticationUtils $authenticationUtils,Request $request): Response
    {
      
        $error = $authenticationUtils->getLastAuthenticationError();
        
        $lastUsername = $authenticationUtils->getLastUsername();
        $verif=$request->request->get('verif');
       
        return $this->render('Parent/account/connexion.html.twig', [
            
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/logout', name: 'app.logout')]
    public function logout(): void
    {
        // Cette méthode ne contient pas de code car Symfony gère automatiquement le processus de déconnexion.
        // La configuration appropriée doit être en place dans le fichier security.yaml.
        // Par défaut, Symfony redirigera l'utilisateur vers la page de login après la déconnexion.
        // Vous pouvez personnaliser ce comportement en configurant la redirection de déconnexion dans security.yaml.
       // return $this->redirectToRoute('app.secu');
    }
}

