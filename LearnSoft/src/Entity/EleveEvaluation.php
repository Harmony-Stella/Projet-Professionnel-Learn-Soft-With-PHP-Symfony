<?php

namespace App\Entity;

use App\Repository\EleveEvaluationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EleveEvaluationRepository::class)]
class EleveEvaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer',nullable: true)]
    private $id;

    #[ORM\Column(type: 'integer',nullable: true)]
    private $note;

    #[ORM\Column(type: 'string', length: 60,nullable: true)]
    private $tempsMis;

    #[ORM\Column(type: 'string', length: 255,nullable: true)]
    private $beginAt;

    #[ORM\Column(type: 'string', length: 255,nullable: true)]
    private $endAt;

    #[ORM\ManyToOne(targetEntity: Evaluation::class, inversedBy: 'eleveEvaluations')]
    #[ORM\JoinColumn(nullable: false)]
    private $evaluation;

    #[ORM\ManyToOne(targetEntity: Eleve::class, inversedBy: 'eleveEvaluations')]
    #[ORM\JoinColumn(nullable: false)]
    private $eleve;

    #[ORM\Column(type: 'integer',nullable: true)]
    private $progression;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $etat;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $repriseAt;

    #[ORM\Column(type: 'integer')]
    private $tentativeRestante;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $correction;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $dureeRestant;

    #[ORM\Column(type: 'integer',nullable: true)]
    private $noteInitiale;

    #[ORM\Column(type: 'float', nullable: true)]
    private $sur20;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getTempsMis(): ?string
    {
        return $this->tempsMis;
    }

    public function setTempsMis(string $tempsMis): self
    {
        $this->tempsMis = $tempsMis;

        return $this;
    }

    public function getBeginAt(): ?string
    {
        return $this->beginAt;
    }

    public function setBeginAt(string $beginAt): self
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    public function getEndAt(): ?string
    {
        return $this->endAt;
    }

    public function setEndAt(string $endAt): self
    {
        $this->endAt = $endAt;

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

    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve): self
    {
        $this->eleve = $eleve;

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

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(?bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getRepriseAt(): ?string
    {
        return $this->repriseAt;
    }

    public function setRepriseAt(?string $repriseAt): self
    {
        $this->repriseAt = $repriseAt;

        return $this;
    }

    public function getTentativeRestante(): ?int
    {
        return $this->tentativeRestante;
    }

    public function setTentativeRestante(int $tentativeRestante): self
    {
        $this->tentativeRestante = $tentativeRestante;

        return $this;
    }

    public function isCorrection(): ?bool
    {
        return $this->correction;
    }

    public function setCorrection(?bool $correction): self
    {
        $this->correction = $correction;

        return $this;
    }

    public function getDureeRestant(): ?string
    {
        return $this->dureeRestant;
    }

    public function setDureeRestant(?string $dureeRestant): self
    {
        $this->dureeRestant = $dureeRestant;

        return $this;
    }

    public function getNoteInitiale(): ?int
    {
        return $this->noteInitiale;
    }

    public function setNoteInitiale(int $noteInitiale): self
    {
        $this->noteInitiale = $noteInitiale;

        return $this;
    }

    public function getSur20(): ?float
    {
        return $this->sur20;
    }

    public function setSur20(?float $sur20): self
    {
        $this->sur20 = $sur20;

        return $this;
    }
}
