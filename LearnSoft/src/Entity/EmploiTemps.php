<?php

namespace App\Entity;

use App\Repository\EmploiTempsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmploiTempsRepository::class)]
class EmploiTemps
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string')]
    private $heure_debut;

    #[ORM\Column(type: 'string')]
    private $heure_fin;

    #[ORM\Column(type: 'string', length: 255)]
    private $jour;

    #[ORM\Column(type: 'string', length: 255)]
    private $titre_event;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\ManyToOne(targetEntity: Parents::class, inversedBy: 'emploi_temps')]
    #[ORM\JoinColumn(nullable: false)]
    private $parent;

    #[ORM\Column(type: 'integer')]
    private $classe;

    /*#[ORM\ManyToOne(targetEntity: Classe::class, inversedBy: 'emploiTemps')]
    #[ORM\JoinColumn(nullable: false)]
    private $classe;*/

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeureDebut(): ?string
    {
        return $this->heure_debut;
    }

    public function setHeureDebut(string $heure_debut): self
    {
        $this->heure_debut = $heure_debut;

        return $this;
    }

    public function getHeureFin(): ?string
    {
        return $this->heure_fin;
    }

    public function setHeureFin(string $heure_fin): self
    {
        $this->heure_fin = $heure_fin;

        return $this;
    }

    public function getJour(): ?string
    {
        return $this->jour;
    }

    public function setJour(string $jour): self
    {
        $this->jour = $jour;

        return $this;
    }

    public function getTitreEvent(): ?string
    {
        return $this->titre_event;
    }

    public function setTitreEvent(string $titre_event): self
    {
        $this->titre_event = $titre_event;

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

    public function getParents(): ?Parents
    {
        return $this->parents;
    }

    public function setParents(?Parents $parents): self
    {
        $this->parents = $parents;

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

    /*public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): self
    {
        $this->classe = $classe;

        return $this;
    }*/

    public function getClasse(): ?int
    {
        return $this->classe;
    }

    public function setClasse(int $classe): self
    {
        $this->classe = $classe;

        return $this;
    }
}
