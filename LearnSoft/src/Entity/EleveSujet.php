<?php

namespace App\Entity;

use App\Repository\EleveSujetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EleveSujetRepository::class)]
class EleveSujet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string',nullable: true)]
    private $reponse;

    #[ORM\Column(type: 'integer',nullable: true)]
    private $etatReponse;

    #[ORM\ManyToOne(targetEntity: Eleve::class, inversedBy: 'eleveSujet')]
    #[ORM\JoinColumn(nullable: false)]
    private $eleve;

    #[ORM\ManyToOne(targetEntity: Sujet::class, inversedBy: 'eleveSujet')]
    #[ORM\JoinColumn(nullable: false)]
    private $sujet;

    #[ORM\Column(type: 'boolean')]
    private $etat;

    #[ORM\Column(type: 'float',nullable: true)]
    private $noteRecu;

    #[ORM\ManyToOne(targetEntity: Evaluation::class, inversedBy: 'eleveSujet')]
    #[ORM\JoinColumn(nullable: false)]
    private $evaluation;

    #[ORM\Column(type: 'string', length: 300, nullable: true)]
    private $apport;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getEtatReponse(): ?int
    {
        return $this->etatReponse;
    }

    public function setEtatReponse(int $etatReponse): self
    {
        $this->etatReponse = $etatReponse;

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

    public function getSujet(): ?Sujet
    {
        return $this->sujet;
    }

    public function setSujet(?Sujet $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(?bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getNoteRecu(): ?float
    {
        return $this->noteRecu;
    }

    public function setNoteRecu(?float $noteRecu): self
    {
        $this->noteRecu = $noteRecu;

        return $this;
    }

    public function getEvaluation(): ?Evaluation
    {
        return $this->evaluation;
    }

    public function setEvaluation(?Evaluation $evaluation): self
    {
        $this->evaluation = $evaluation;

        return $this;
    }

    public function getApport(): ?string
    {
        return $this->apport;
    }

    public function setApport(?string $apport): self
    {
        $this->apport = $apport;

        return $this;
    }
}
