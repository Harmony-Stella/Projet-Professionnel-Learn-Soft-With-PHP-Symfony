<?php

namespace App\Controller\ParentController;

use App\Entity\Utilisateur;
use App\Entity\Eleve;
use App\Entity\Classe;
use App\Entity\Parents;
use App\Form\ClasseType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use App\Repository\EleveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use App\Repository\ClasseRepository;
use App\Repository\DeconnexionRepository;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
class RegisterParentController extends AbstractController
{
    public function __construct(AuthorizationCheckerInterface $authorizationChecker,UtilisateurRepository $repoUtilisateur, EntityManagerInterface $em,Security $security,EleveRepository $repoEleve,ClasseRepository $repoClasse,DeconnexionRepository $repoDeconnexion)
    {
        $this->repoUtilisateur = $repoUtilisateur;
        $this->repoDeconnexion = $repoDeconnexion;
        $this->repoEleve = $repoEleve;
        $this->em = $em;
        $this->security = $security;
        $this->authorizationChecker = $authorizationChecker;
        $this->repoClasse = $repoClasse;

    }
   #[Route('/inscription', name: 'app.inscription')]
    public function inscription(Request $request , UserPasswordHasherInterface $passwordHasher): Response
    {
        $utilisateur = new Utilisateur;
        $parent = new Parents;
        $nom=$request->request->get('first-name');
        $username=$request->request->get('username');
        $prenom=$request->request->get('last-name');
        $email=$request->request->get('email');
        $contact=$request->request->get('contact');
        $sexe=$request->request->get('sexe');
        $pays=$request->request->get('pays');
        $profession=$request->request->get('profession');
        $password=$request->request->get('password');
        //dd('e');
        if ($request->isXmlHttpRequest()) {
           
            if ($nom==null OR $username==null OR $prenom==null OR $email==null OR $contact==null OR $password==null OR $sexe==null OR $pays==null OR $profession==null){
                    
                $message="Tout les champs sont requis";            
                return $this->json($message);

            }
            $allUtilisateur = $this->repoUtilisateur->findAll();
                foreach ($allUtilisateur as $key => $value) {
                    if($value->getUsername()==$username){
                        $message="Nom d'utilisateur existant";            
                        return $this->json($message);
                    }
                    if($value->getEmail()==$email){
                        $message="Adresse email existante";            
                        return $this->json($message);
                    }
                    if($value->getContact()==$contact){
                        $message="Numéro de téléphone existant";            
                        return $this->json($message);
                    }
                }
            if (isset($nom,$username,$prenom,$email,$contact,$password,$sexe,$pays,$profession)){
            $parent->setProfession($profession);
            $this->em->persist($parent);
            $this->em->flush();
            $utilisateur->setNom($nom);
            $utilisateur->setPrenom($prenom);
            $utilisateur->setUsername($username);
            $utilisateur->setEmail($email);
            $utilisateur->setContact($contact);
            $utilisateur->setSexe($sexe);
            $utilisateur->setPays($pays);
            $utilisateur->setParent($parent);
            $hashedPassword = $passwordHasher->hashPassword($utilisateur,$password);
            $utilisateur->setPassword($hashedPassword);
            $utilisateur->setRoles(['ROLE_PARENT']);

            $this->em->persist($utilisateur);
            $this->em->flush();
            return new JsonResponse([
                'success' => true,
                'redirect_url' => $this->generateUrl('app.connexion'),
            ]);
            //return $this->redirectToRoute('app.connexion');
            
            }
            else{
                return $this->render('Parent/account/inscription.html.twig', [
                'controller_name' => 'SecurityController',
                ]);
            }
        }else{
            return $this->render('Parent/account/inscription.html.twig', [
                'controller_name' => 'SecurityController',
            ]);
        }
        
    }

    #[Route('/parent_update', name: 'app.parent.update')]
    public function parent_update(Request $request , UserPasswordHasherInterface $passwordHasher): Response
    {
        $parent = $this->getUser()->getParent();
        $utilisateur = $this->getUser();
        $nom=$request->request->get('first-name');
        $username=$request->request->get('username');
        $prenom=$request->request->get('last-name');
        $email=$request->request->get('email');
        $contact=$request->request->get('contact');
        $sexe=$request->request->get('sexe');
        $pays=$request->request->get('pays');
        $profession=$request->request->get('profession');
        $password=$request->request->get('password');
        //dd('e');
        if ($request->isXmlHttpRequest()) {
           
            if ($nom==null OR $username==null OR $prenom==null OR $email==null OR $contact==null OR $sexe==null OR $profession==null){
                    
                $message="Tout les champs sont requis";            
                return $this->json($message);

            }
            $allUtilisateur = $this->repoUtilisateur->findAll();
                foreach ($allUtilisateur as $key => $value) {
                    if($value->getUsername()==$username AND $value->getId()!=$this->getUser()->getId()){
                        $message="Nom d'utilisateur existant";            
                        return $this->json($message);
                    }
                    if($value->getEmail()==$email AND $value->getId()!=$this->getUser()->getId()){
                        $message="Adresse email existante";            
                        return $this->json($message);
                    }
                    if($value->getContact()==$contact AND $value->getId()!=$this->getUser()->getId()){
                        $message="Numéro de téléphone existant";            
                        return $this->json($message);
                    }
                }
            if (isset($nom,$username,$prenom,$email,$contact,$sexe,$profession)){
            $parent->setProfession($profession);
            $this->em->persist($parent);
            $this->em->flush();
            $utilisateur->setNom($nom);
            $utilisateur->setPrenom($prenom);
            $utilisateur->setUsername($username);
            $utilisateur->setEmail($email);
            $utilisateur->setContact($contact);
            $utilisateur->setSexe($sexe);
            //$utilisateur->setPays($pays);
            //$utilisateur->setParent($parent);
            if($password != null){
                $hashedPassword = $passwordHasher->hashPassword($utilisateur,$password);
                $utilisateur->setPassword($hashedPassword);
            }
           
            //$utilisateur->setRoles(['ROLE_PARENT']);

            $this->em->persist($utilisateur);
            $this->em->flush();
            return new JsonResponse([
                'success' => true,
                'redirect_url' => $this->generateUrl('apps.compte.apercu'),
            ]);
            //return $this->redirectToRoute('app.connexion');
            
            }
            else{
                return $this->render('Parent/account/inscription.html.twig', [
                'controller_name' => 'SecurityController',
                ]);
            }
        }else{
            return $this->render('Parent/account/inscription.html.twig', [
                'controller_name' => 'SecurityController',
            ]);
        }
        
    }
    /*#[Route('/inscription1', name: 'app.inscription1')]
    public function inscription1(Request $request,UserPasswordHasherInterface $passwordHasher): Response
    {
        

         $utilisateur = new Utilisateur;
            $parent = new Parents;
            $formAjout = $this->createForm(UserType::class, $utilisateur);
            $formAjout ->handleRequest($request);
            if( $formAjout->isSubmitted() && $formAjout->isValid()){
                 
            $nom=$request->request->get('first-name');
            $username=$request->request->get('username');
            $prenom=$request->request->get('last-name');
            $email=$request->request->get('email');
            $contact=$request->request->get('contact');
          // $profession=$request->request->get('profession');
            $password=$request->request->get('password');
            //$confirm_password=$request->request->get('confirm-password');
            if (isset($nom,$username,$prenom,$email,$contact,$password)){
               $utilisateur->setNom($nom);
               $utilisateur->setPrenom($prenom);
               $utilisateur->setUsername($username);
               $utilisateur->setEmail($email);
               $utilisateur->setContact($contact);
               //$utilisateur->setParent($parent->getId());
             // $parent->setProfession($profession);
               $hashedPassword = $passwordHasher->hashPassword($utilisateur,$password);
               $utilisateur->setPassword($hashedPassword);
               $utilisateur->setRoles(['ROLE_PARENT']);

               $this->em->persist($utilisateur);
               //$this->em->persist($parent);
               $this->em->flush(); 
            }
        }
        return $this->render('Parent/account/inscription.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }*/
}
