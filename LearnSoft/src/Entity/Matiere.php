<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatiereRepository::class)]
class Matiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom_matiere;

    #[ORM\ManyToOne(targetEntity: Classe::class, inversedBy: 'matiere')]
    #[ORM\JoinColumn(nullable: false)]
    private $classe;

    #[ORM\OneToMany(mappedBy: 'matiere', targetEntity: Evaluation::class)]
    private $evaluation;

    #[ORM\OneToMany(mappedBy: 'matiere', targetEntity: ProgressionJour::class)]
    private $progressionJour;

    public function __construct()
    {
        $this->evaluation = new ArrayCollection();
        $this->progressionJour = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMatiere(): ?string
    {
        return $this->nom_matiere;
    }

    public function setNomMatiere(string $nom_matiere): self
    {
        $this->nom_matiere = $nom_matiere;

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

    /**
     * @return Collection<int, Evaluation>
     */
    public function getEvaluation(): Collection
    {
        return $this->evaluation;
    }

    public function addEvaluation(Evaluation $evaluation): self
    {
        if (!$this->evaluation->contains($evaluation)) {
            $this->evaluation[] = $evaluation;
            $evaluation->setMatiere($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): self
    {
        if ($this->evaluation->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getMatiere() === $this) {
                $evaluation->setMatiere(null);
            }
        }

        return $this;
    }

    public function __toString(){

        return $this->nom_matiere;
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
            $progressionJour->setMatiere($this);
        }

        return $this;
    }

    public function removeProgressionJour(ProgressionJour $progressionJour): self
    {
        if ($this->progressionJour->removeElement($progressionJour)) {
            // set the owning side to null (unless already changed)
            if ($progressionJour->getMatiere() === $this) {
                $progressionJour->setMatiere(null);
            }
        }

        return $this;
    }

   

}
