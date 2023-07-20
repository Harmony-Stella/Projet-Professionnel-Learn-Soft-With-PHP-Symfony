<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping\InheritanceType;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity('username')]
#[InheritanceType("JOINED")]
#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\Column(type: 'string', length: 55,unique:true)]
    protected $username;

    #[ORM\Column(type: 'string', length: 55)]
    protected $nom;

    #[ORM\Column(type: 'string', length: 55)]
    protected $prenom;

    #[ORM\Column(type: 'string', length: 55, unique:true)]
    
    protected $email;

    #[ORM\Column(type: 'integer')]
    protected $contact;

    #[ORM\Column(type: 'string', length: 255)]
    protected $password;

    #[ORM\Column(type:"json")]
     
    protected $roles = [];

    #[ORM\Column(type: 'string', length: 255)]
    protected $sexe;

    #[ORM\Column(type: 'string', length: 255)]
    protected $pays;


    #[ORM\ManyToOne(targetEntity: Eleve::class, inversedBy: 'utilisateurs',cascade: ['persist', 'remove'])]
    protected $eleve;

    #[ORM\ManyToOne(targetEntity: Parents::class, inversedBy: 'utilisateurs',cascade: ['persist', 'remove'])]
    protected $parent;

    #[ORM\ManyToOne(targetEntity: Roles::class, inversedBy: 'utilisateurs',cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    protected $role;

    #[ORM\ManyToOne(targetEntity: Classe::class, inversedBy: 'utilisateurs')]
    private $classe;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $online;


    public function getId(): ?int
    {
        return $this->id;
    }



    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getContact(): ?int
    {
        return $this->contact;
    }

    public function setContact(int $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve): self
    {
        $this->eleve = $eleve;

        return $this;
    }

    public function getParent(): ?Parents
    {
        return $this->parent;
    }

    public function setParent(?Parents $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

   
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

  
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getRole(): ?Roles
    {
        return $this->role;
    }

    public function setRole(?Roles $role): self
    {
        $this->role = $role;

        return $this;
    }

    

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function isOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(?bool $online): self
    {
        $this->online = $online;

        return $this;
    }
}
