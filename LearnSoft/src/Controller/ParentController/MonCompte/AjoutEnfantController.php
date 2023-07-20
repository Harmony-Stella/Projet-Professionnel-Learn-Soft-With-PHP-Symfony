<?php

namespace App\Controller\ParentController\MonCompte;

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

class AjoutEnfantController extends AbstractController
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

    #[Route('/parent/compteAjoutEnfant-{id}', name: 'apps.compte.ajout-enfant')]
    public function ajoutEnfant(Security $security,Request $request, UserPasswordHasherInterface $passwordHasher,Parents $parent, int $id): Response
    {  

        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            
            return $this->redirectToRoute('app.connexion');
            
        }
        else{
            if($this->authorizationChecker->isGranted('ROLE_ELEVE')){

                return $this->redirectToRoute('app.connexion');
                
            }
        }

      $mesEleve = $this->repoEleve->findEleve($id);
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

      $LesClasses = $this->repoClasse->LesClasses();

      $utilisateur = new Utilisateur;
      $eleve = new Eleve;
            $nom=$request->request->get('first-name');
            $username=$request->request->get('username');
            $prenom=$request->request->get('last-name');
            $email=$request->request->get('email');
            $contact=$request->request->get('contact');
            $password=$request->request->get('password');
            $classe=$request->request->get('classe');
            $date=$request->request->get('date');
            $sexe=$request->request->get('sexe');
            $pays=$request->request->get('pays');
            if ($request->isXmlHttpRequest()) {
                if ($nom==null OR $username==null OR $prenom==null OR $email==null OR $contact==null OR $password==null OR $classe==null OR $date==null OR $sexe==null OR $pays==null){
                    
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
                if(isset($nom,$username,$prenom,$email,$contact,$password,$classe,$date,$sexe,$pays)){

                    foreach($LesClasses as $item=>$value){
                            if ($classe==$value->getId()) {
                                
                                $objetClasse=$value;

                            }
                        }
                    $eleve->setParents($parent);
                    $eleve->setDateNaissance($date);
                    $this->em->persist($eleve);
                    
                    $utilisateur->setNom($nom);
                    $utilisateur->setPrenom($prenom);
                    $utilisateur->setUsername($username);
                    $utilisateur->setEmail($email);
                    $utilisateur->setContact($contact);
                    $utilisateur->setClasse($objetClasse);
                    $utilisateur->setSexe($sexe);
                    $utilisateur->setPays($pays); 
                    $utilisateur->setEleve($eleve);    
                    $hashedPassword = $passwordHasher->hashPassword($utilisateur,$password);
                    $utilisateur->setPassword($hashedPassword);
                    $utilisateur->setRoles(['ROLE_ELEVE']);
                    $this->em->persist($utilisateur);
                    //dd($objetClasse);
                    $this->em->flush();
                    /*$email = (new Email())
                        ->from('toviawoukplolacrepin@gmail.com')
                        ->to('crepintoviawou@gmail.com')
                        ->subject('Time for Symfony Mailer!')
                        ->text('Sending emails is fun again!')
                        ->html('<p>COUCOU</p>')
                        ;

                        dd($mailer);
                    ($mailer->send($email));*/
                    return new JsonResponse([
                        'success' => true,
                        'redirect_url' => $this->generateUrl('apps.compte.ajout-enfant', ['id' => $id]),
                    ]);

                    
                
                }
                else{
                    return $this->render('Parent/account/ajout-enfant.html.twig', [
                        'controller_name' => 'AjoutEnfantController',
                        'parent'=>$parent,
                        'mesEleves'=>$MesEleves,
                        'mesEleve'=>$mesEleve,
                        'lesClasses'=>$LesClasses,
                        'LoginParent'=>$LoginParent
                    ]);
                }

            }else{
            return $this->render('Parent/account/ajout-enfant.html.twig', [
                'controller_name' => 'AjoutEnfantController',
                'parent'=>$parent,
                'mesEleves'=>$MesEleves,
                'mesEleve'=>$mesEleve,
                'lesClasses'=>$LesClasses,
                'LoginParent'=>$LoginParent
            ]);
        }
           
        
    }

    #[Route('/parent/compteAffichage', name: 'apps.compte.affichage')]
    public function affichageEnfant(Security $security,Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            
            return $this->redirectToRoute('app.connexion');
            
        }
        else{
            if($this->authorizationChecker->isGranted('ROLE_ELEVE')){

                return $this->redirectToRoute('app.connexion');
                
            }
        }
          $eleve1 = $this->repoUtilisateur->findEleve();
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
         return $this->render('Parent/account/ajout-enfant.html.twig', [
            'controller_name' => 'AjoutEnfantController',
            'mesEleves'=>$MesEleves,
            'LoginParent'=>$LoginParent
            
            
        ]);
       
    }
}
