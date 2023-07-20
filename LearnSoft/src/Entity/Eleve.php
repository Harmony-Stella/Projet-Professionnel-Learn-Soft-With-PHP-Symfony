<?php

namespace App\Entity;

use App\Entity\Utilisateur;
use App\Repository\EleveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EleveRepository::class)]
class Eleve
{
   // private $uti = new Utilisateur;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

     #[ORM\Column(type: 'string', length:255)]
    private $date_naissance;


    #[ORM\OneToMany(mappedBy: 'eleve', targetEntity: Utilisateur::class, orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: false)]
    private $utilisateurs;

   
    #[ORM\ManyToOne(targetEntity: Parents::class, inversedBy: 'eleves',cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $parents;

    #[ORM\OneToMany(mappedBy: 'eleve', targetEntity: EleveEvaluation::class)]
    private $eleveEvaluations;

    #[ORM\OneToMany(mappedBy: 'eleve', targetEntity: EleveSujet::class)]
    private $eleveSujet;

    #[ORM\OneToMany(mappedBy: 'eleve', targetEntity: EleveProposition::class)]
    private $eleve_proposition;

    #[ORM\OneToMany(mappedBy: 'eleve', targetEntity: ProgressionJour::class)]
    private $progressionJour;

    #[ORM\OneToMany(mappedBy: 'eleve', targetEntity: Deconnexion::class)]
    private $deconnexion;

   

    public function __construct()
    {
        $this->utilisateurs = new ArrayCollection();
        $this->eleveEvaluations = new ArrayCollection();
        $this->eleveSujet = new ArrayCollection();
        $this->eleve_proposition = new ArrayCollection();
        $this->progressionJour = new ArrayCollection();
        $this->deconnexion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): self
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs[] = $utilisateur;
            $utilisateur->setEleve($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            // set the owning side to null (unless already changed)
            if ($utilisateur->getEleve() === $this) {
                $utilisateur->setEleve(null);
            }
        }

        return $this;
    }

    public function getDateNaissance(): ?string
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(string $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getParents(): ?Parents
    {
        return $this->parents;
    }

    public function setParents(?Parents $parents): self
    {
        $this->parents = $parents;

        return $this;
    }

    /**
     * @return Collection<int, EleveEvaluation>
     */
    public function getEleveEvaluations(): Collection
    {
        return $this->eleveEvaluations;
    }

    public function addEleveEvaluation(EleveEvaluation $eleveEvaluation): self
    {
        if (!$this->eleveEvaluations->contains($eleveEvaluation)) {
            $this->eleveEvaluations[] = $eleveEvaluation;
            $eleveEvaluation->setEleve($this);
        }

        return $this;
    }

    public function removeEleveEvaluation(EleveEvaluation $eleveEvaluation): self
    {
        if ($this->eleveEvaluations->removeElement($eleveEvaluation)) {
            // set the owning side to null (unless already changed)
            if ($eleveEvaluation->getEleve() === $this) {
                $eleveEvaluation->setEleve(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EleveSujet>
     */
    public function getEleveSujet(): Collection
    {
        return $this->eleveSujet;
    }

    public function addEleveSujet(EleveSujet $eleveSujet): self
    {
        if (!$this->eleveSujet->contains($eleveSujet)) {
            $this->eleveSujet[] = $eleveSujet;
            $eleveSujet->setEleve($this);
        }

        return $this;
    }

    public function removeEleveSujet(EleveSujet $eleveSujet): self
    {
        if ($this->eleveSujet->removeElement($eleveSujet)) {
            // set the owning side to null (unless already changed)
            if ($eleveSujet->getEleve() === $this) {
                $eleveSujet->setEleve(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EleveProposition>
     */
    public function getEleveProposition(): Collection
    {
        return $this->eleve_proposition;
    }

    public function addEleveProposition(EleveProposition $eleveProposition): self
    {
        if (!$this->eleve_proposition->contains($eleveProposition)) {
            $this->eleve_proposition[] = $eleveProposition;
            $eleveProposition->setEleve($this);
        }

        return $this;
    }

    public function removeEleveProposition(EleveProposition $eleveProposition): self
    {
        if ($this->eleve_proposition->removeElement($eleveProposition)) {
            // set the owning side to null (unless already changed)
            if ($eleveProposition->getEleve() === $this) {
                $eleveProposition->setEleve(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProgressionJour>
     */
    public function getProgressionJour(): Collection
    {
        return $this->progressionJour;
    }

    public function addProgressionJour(ProgressionJour $progressionJour): self
    {
        if (!$this->progressionJour->contains($progressionJour)) {
            $this->progressionJour[] = $progressionJour;
            $progressionJour->setEleve($this);
        }

        return $this;
    }

    public function removeProgressionJour(ProgressionJour $progressionJour): self
    {
        if ($this->progressionJour->removeElement($progressionJour)) {
            // set the owning side to null (unless already changed)
            if ($progressionJour->getEleve() === $this) {
                $progressionJour->setEleve(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Deconnexion>
     */
    public function getDeconnexion(): Collection
    {
        return $this->deconnexion;
    }

    public function addDeconnexion(Deconnexion $deconnexion): self
    {
        if (!$this->deconnexion->contains($deconnexion)) {
            $this->deconnexion[] = $deconnexion;
            $deconnexion->setEleve($this);
        }

        return $this;
    }

    public function removeDeconnexion(Deconnexion $deconnexion): self
    {
        if ($this->deconnexion->removeElement($deconnexion)) {
            // set the owning side to null (unless already changed)
            if ($deconnexion->getEleve() === $this) {
                $deconnexion->setEleve(null);
            }
        }

        return $this;
    }

    
}
