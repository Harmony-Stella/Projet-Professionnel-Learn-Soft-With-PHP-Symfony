<?php

namespace App\Entity;

use App\Repository\PropositionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropositionsRepository::class)]
class Propositions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255,nullable:true)]
    private $libelle;

    #[ORM\Column(type: 'boolean', length: 255)]
    private $reponseValide;

    #[ORM\ManyToOne(targetEntity: Sujet::class, inversedBy: 'proposition')]
    #[ORM\JoinColumn(nullable: false)]
    private $sujet;

    #[ORM\OneToMany(mappedBy: 'propositions', targetEntity: EleveProposition::class)]
    private $eleve_propositions;

    public function __construct()
    {
        $this->eleve_propositions = new ArrayCollection();
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

     public function isReponseValide(): ?bool
    {
        return $this->reponseValide;
    }

    public function setReponseValide(?bool $reponseValide): self
    {
        $this->reponseValide = $reponseValide;

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

    /**
     * @return Collection<int, EleveProposition>
     */
    public function getElevePropositions(): Collection
    {
        return $this->eleve_propositions;
    }

    public function addEleveProposition(EleveProposition $eleveProposition): self
    {
        if (!$this->eleve_propositions->contains($eleveProposition)) {
            $this->eleve_propositions[] = $eleveProposition;
            $eleveProposition->setPropositions($this);
        }

        return $this;
    }

    public function removeEleveProposition(EleveProposition $eleveProposition): self
    {
        if ($this->eleve_propositions->removeElement($eleveProposition)) {
            // set the owning side to null (unless already changed)
            if ($eleveProposition->getPropositions() === $this) {
                $eleveProposition->setPropositions(null);
            }
        }

        return $this;
    }
}
