<?php

namespace App\Entity;

use App\Repository\ElevePropositionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ElevePropositionRepository::class)]
class EleveProposition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'boolean')]
    private $reponseEleve;

    #[ORM\Column(type: 'float')]
    private $noteRecu;

    #[ORM\ManyToOne(targetEntity: Eleve::class, inversedBy: 'eleve_proposition')]
    #[ORM\JoinColumn(nullable: false)]
    private $eleve;

    #[ORM\ManyToOne(targetEntity: Propositions::class, inversedBy: 'eleve_propositions')]
    #[ORM\JoinColumn(nullable: false)]
    private $propositions;

    #[ORM\ManyToOne(targetEntity: Sujet::class, inversedBy: 'eleve_proposition')]
    #[ORM\JoinColumn(nullable: false)]
    private $sujet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isReponseEleve(): ?bool
    {
        return $this->reponseEleve;
    }

    public function setReponseEleve(bool $reponseEleve): self
    {
        $this->reponseEleve = $reponseEleve;

        return $this;
    }

    public function getNoteRecu(): ?float
    {
        return $this->noteRecu;
    }

    public function setNoteRecu(float $noteRecu): self
    {
        $this->noteRecu = $noteRecu;

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

    public function getPropositions(): ?Propositions
    {
        return $this->propositions;
    }

    public function setPropositions(?Propositions $propositions): self
    {
        $this->propositions = $propositions;

        return $this;
    }

    public function getSujet(): ?Sujet
    {
        return $this->sujet;
    }

    public function setSujet(?Sujet $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }
}
