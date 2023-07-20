<?php

namespace App\Entity;

use App\Repository\ProgressionJourRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgressionJourRepository::class)]
class ProgressionJour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $dateDuJour;

    #[ORM\Column(type: 'integer')]
    private $progression;

    #[ORM\ManyToOne(targetEntity: Eleve::class, inversedBy: 'progressionJour')]
    #[ORM\JoinColumn(nullable: false)]
    private $eleve;

    #[ORM\ManyToOne(targetEntity: Matiere::class, inversedBy: 'progressionJour')]
    #[ORM\JoinColumn(nullable: false)]
    private $matiere;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDuJour(): ?string
    {
        return $this->dateDuJour;
    }

    public function setDateDuJour(string $dateDuJour): self
    {
        $this->dateDuJour = $dateDuJour;

        return $this;
    }

    public function getProgression(): ?int
    {
        return $this->progression;
    }

    public function setProgression(int $progression): self
    {
        $this->progression = $progression;

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

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }
}
