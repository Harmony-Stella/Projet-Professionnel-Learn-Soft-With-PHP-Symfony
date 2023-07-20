<?php

namespace App\Entity;

use App\Repository\SujetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SujetRepository::class)]
class Sujet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $question;

    #[ORM\Column(type: 'integer')]
    private $notation;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $etat;

    #[ORM\ManyToOne(targetEntity: Evaluation::class, inversedBy: 'sujet')]
    #[ORM\JoinColumn(nullable: false)]
    private $evaluation;

    #[ORM\OneToMany(mappedBy: 'sujet', targetEntity: Propositions::class)]
    private $proposition;

    #[ORM\OneToMany(mappedBy: 'sujet', targetEntity: EleveSujet::class)]
    private $eleveSujet;

    #[ORM\Column(type: 'boolean')]
    private $type_evaluation;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $nombre;

    #[ORM\OneToMany(mappedBy: 'sujet', targetEntity: EleveProposition::class)]
    private $eleve_proposition;


    public function __construct()
    {
        $this->proposition = new ArrayCollection();
        $this->eleveSujet = new ArrayCollection();
        $this->eleve_proposition = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }


    public function getNotation(): ?int
    {
        return $this->notation;
    }

    public function setNotation(int $notation): self
    {
        $this->notation = $notation;

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

    public function getEvaluation(): ?Evaluation
    {
        return $this->evaluation;
    }

    public function setEvaluation(?Evaluation $evaluation): self
    {
        $this->evaluation = $evaluation;

        return $this;
    }

    /**
     * @return Collection<int, Propositions>
     */
    public function getProposition(): Collection
    {
        return $this->proposition;
    }

    public function addProposition(Propositions $proposition): self
    {
        if (!$this->proposition->contains($proposition)) {
            $this->proposition[] = $proposition;
            $proposition->setSujet($this);
        }

        return $this;
    }

    public function removeProposition(Propositions $proposition): self
    {
        if ($this->proposition->removeElement($proposition)) {
            // set the owning side to null (unless already changed)
            if ($proposition->getSujet() === $this) {
                $proposition->setSujet(null);
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
            $eleveSujet->setSujet($this);
        }

        return $this;
    }

    public function removeEleveSujet(EleveSujet $eleveSujet): self
    {
        if ($this->eleveSujet->removeElement($eleveSujet)) {
            // set the owning side to null (unless already changed)
            if ($eleveSujet->getSujet() === $this) {
                $eleveSujet->setSujet(null);
            }
        }

        return $this;
    }

    public function isTypeEvaluation(): ?bool
    {
        return $this->type_evaluation;
    }

    public function setTypeEvaluation(bool $type_evaluation): self
    {
        $this->type_evaluation = $type_evaluation;

        return $this;
    }

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(?int $nombre): self
    {
        $this->nombre = $nombre;

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
            $eleveProposition->setSujet($this);
        }

        return $this;
    }

    public function removeEleveProposition(EleveProposition $eleveProposition): self
    {
        if ($this->eleve_proposition->removeElement($eleveProposition)) {
            // set the owning side to null (unless already changed)
            if ($eleveProposition->getSujet() === $this) {
                $eleveProposition->setSujet(null);
            }
        }

        return $this;
    }

    
}
