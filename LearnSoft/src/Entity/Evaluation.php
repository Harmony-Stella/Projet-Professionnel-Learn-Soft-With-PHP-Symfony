<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvaluationRepository::class)]
class Evaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 60)]
    private $titre;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    /*#[ORM\Column(type: 'integer')]
    private $classe;*/

    #[ORM\Column(type: 'string', length: 255)]
    private $dateDebut;

    #[ORM\Column(type: 'string', length: 255)]
    private $dateFin;

    #[ORM\Column(type: 'string', length: 255)]
    private $heureDebut;

    #[ORM\Column(type: 'string', length: 255)]
    private $heureFin;

    #[ORM\Column(type: 'boolean', length: 255)]
    private $statut;

    #[ORM\Column(type: 'string', length: 255)]
    private $etat;

    #[ORM\Column(type: 'string', length: 60, nullable: true)]
    private $duree;

    #[ORM\Column(type: 'integer')]
    private $tentative;

    #[ORM\Column(type: 'integer')]
    private $noteSur;

    #[ORM\OneToMany(mappedBy: 'evaluation', targetEntity: EleveEvaluation::class)]
    private $eleveEvaluations;

    #[ORM\OneToMany(mappedBy: 'evaluation', targetEntity: Sujet::class)]
    private $sujet;

    #[ORM\ManyToOne(targetEntity: Parents::class, inversedBy: 'evaluation')]
    #[ORM\JoinColumn(nullable: false)]
    private $parents;

    #[ORM\Column(type: 'integer')]
    private $niveauEvaluation;

    #[ORM\ManyToOne(targetEntity: Classe::class, inversedBy: 'evaluation')]
    #[ORM\JoinColumn(nullable: false)]
    private $classe;

    #[ORM\ManyToOne(targetEntity: Matiere::class, inversedBy: 'evaluation')]
    #[ORM\JoinColumn(nullable: false)]
    private $matiere;

    #[ORM\ManyToOne(targetEntity: TypeEvaluation::class, inversedBy: 'evaluations')]
    #[ORM\JoinColumn(nullable: true)]
    private $type_evaluation;

    #[ORM\Column(type: 'string', length: 60, nullable: true)]
    private $ouvreDans;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $overdue;

    #[ORM\Column(type: 'string', length: 60, nullable: true)]
    private $fermeDans;

    #[ORM\OneToMany(mappedBy: 'evaluation', targetEntity: EleveSujet::class)]
    private $eleveSujet;

    

    public function __construct()
    {
        $this->eleves = new ArrayCollection();
        $this->eleveEvaluations = new ArrayCollection();
        $this->sujet = new ArrayCollection();
        $this->eleveSujet = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function getDateDebut(): ?string
    {
        return $this->dateDebut;
    }

    public function setDateDebut(string $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?string
    {
        return $this->dateFin;
    }

    public function setDateFin(string $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getHeureDebut(): ?string
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(string $heureDebut): self
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    public function getHeureFin(): ?string
    {
        return $this->heureFin;
    }

    public function setHeureFin(string $heureFin): self
    {
        $this->heureFin = $heureFin;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(?bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(?string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getTentative(): ?int
    {
        return $this->tentative;
    }

    public function setTentative(int $tentative): self
    {
        $this->tentative = $tentative;

        return $this;
    }

    public function getNoteSur(): ?int
    {
        return $this->noteSur;
    }

    public function setNoteSur(int $noteSur): self
    {
        $this->noteSur = $noteSur;

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
            $eleveEvaluation->setEvaluation($this);
        }

        return $this;
    }

    public function removeEleveEvaluation(EleveEvaluation $eleveEvaluation): self
    {
        if ($this->eleveEvaluations->removeElement($eleveEvaluation)) {
            // set the owning side to null (unless already changed)
            if ($eleveEvaluation->getEvaluation() === $this) {
                $eleveEvaluation->setEvaluation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sujet>
     */
    public function getSujet(): Collection
    {
        return $this->sujet;
    }

    public function addSujet(Sujet $sujet): self
    {
        if (!$this->sujet->contains($sujet)) {
            $this->sujet[] = $sujet;
            $sujet->setEvaluation($this);
        }

        return $this;
    }

    public function removeSujet(Sujet $sujet): self
    {
        if ($this->sujet->removeElement($sujet)) {
            // set the owning side to null (unless already changed)
            if ($sujet->getEvaluation() === $this) {
                $sujet->setEvaluation(null);
            }
        }

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

    public function getNiveauEvaluation(): ?int
    {
        return $this->niveauEvaluation;
    }

    public function setNiveauEvaluation(int $niveauEvaluation): self
    {
        $this->niveauEvaluation = $niveauEvaluation;

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

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getTypeEvaluation(): ?TypeEvaluation
    {
        return $this->type_evaluation;
    }

    public function setTypeEvaluation(?TypeEvaluation $type_evaluation): self
    {
        $this->type_evaluation = $type_evaluation;

        return $this;
    }

    public function getOuvreDans(): ?string
    {
        return $this->ouvreDans;
    }

    public function setOuvreDans(?string $ouvreDans): self
    {
        $this->ouvreDans = $ouvreDans;

        return $this;
    }

    public function isOverdue(): ?bool
    {
        return $this->overdue;
    }

    public function setOverdue(?bool $overdue): self
    {
        $this->overdue = $overdue;

        return $this;
    }

    public function getFermeDans(): ?string
    {
        return $this->fermeDans;
    }

    public function setFermeDans(?string $fermeDans): self
    {
        $this->fermeDans = $fermeDans;

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
            $eleveSujet->setEvaluation($this);
        }

        return $this;
    }

    public function removeEleveSujet(EleveSujet $eleveSujet): self
    {
        if ($this->eleveSujet->removeElement($eleveSujet)) {
            // set the owning side to null (unless already changed)
            if ($eleveSujet->getEvaluation() === $this) {
                $eleveSujet->setEvaluation(null);
            }
        }

        return $this;
    }

  
}
