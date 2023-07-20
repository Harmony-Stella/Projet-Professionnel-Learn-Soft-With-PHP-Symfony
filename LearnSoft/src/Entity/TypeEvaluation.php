<?php

namespace App\Entity;

use App\Repository\TypeEvaluationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeEvaluationRepository::class)]
class TypeEvaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 60)]
    private $libelle;

    #[ORM\OneToMany(mappedBy: 'type_evaluation', targetEntity: Evaluation::class)]
    private $evaluations;

    #[ORM\OneToMany(mappedBy: 'typeEvaluation', targetEntity: Sujet::class)]
    private $sujet;

    public function __construct()
    {
        $this->evaluations = new ArrayCollection();
        $this->sujet = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluation $evaluation): self
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations[] = $evaluation;
            $evaluation->setTypeEvaluation($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): self
    {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getTypeEvaluation() === $this) {
                $evaluation->setTypeEvaluation(null);
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
            $sujet->setTypeEvaluation($this);
        }

        return $this;
    }

    public function removeSujet(Sujet $sujet): self
    {
        if ($this->sujet->removeElement($sujet)) {
            // set the owning side to null (unless already changed)
            if ($sujet->getTypeEvaluation() === $this) {
                $sujet->setTypeEvaluation(null);
            }
        }

        return $this;
    }
}
