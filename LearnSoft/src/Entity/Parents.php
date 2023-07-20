<?php

namespace App\Entity;

use App\Repository\ParentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParentsRepository::class)]
class Parents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $profession;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: EmploiTemps::class, orphanRemoval: true)]
    private $emploi_temps;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Utilisateur::class)]
    private $utilisateurs;

    #[ORM\OneToMany(mappedBy: 'parents', targetEntity: Eleve::class, orphanRemoval: true)]
    private $eleves;

    #[ORM\OneToMany(mappedBy: 'parents', targetEntity: Evaluation::class)]
    private $evaluation;

    public function __construct()
    {
        $this->emploi_temps = new ArrayCollection();
        $this->utilisateurs = new ArrayCollection();
        $this->eleves = new ArrayCollection();
        $this->evaluation = new ArrayCollection();
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * @return Collection<int, EmploiTemps>
     */
    public function getParents(): Collection
    {
        return $this->parents;
    }

    public function addParent(EmploiTemps $parent): self
    {
        if (!$this->parents->contains($parent)) {
            $this->parents[] = $parent;
            $parent->setParents($this);
        }

        return $this;
    }

    public function removeParent(EmploiTemps $parent): self
    {
        if ($this->parents->removeElement($parent)) {
            // set the owning side to null (unless already changed)
            if ($parent->getParents() === $this) {
                $parent->setParents(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EmploiTemps>
     */
    public function getEmploiTemps(): Collection
    {
        return $this->emploi_temps;
    }

    public function addEmploiTemp(EmploiTemps $emploiTemp): self
    {
        if (!$this->emploi_temps->contains($emploiTemp)) {
            $this->emploi_temps[] = $emploiTemp;
            $emploiTemp->setParent($this);
        }

        return $this;
    }

    public function removeEmploiTemp(EmploiTemps $emploiTemp): self
    {
        if ($this->emploi_temps->removeElement($emploiTemp)) {
            // set the owning side to null (unless already changed)
            if ($emploiTemp->getParent() === $this) {
                $emploiTemp->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): self
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs[] = $utilisateur;
            $utilisateur->setParent($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            // set the owning side to null (unless already changed)
            if ($utilisateur->getParent() === $this) {
                $utilisateur->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Eleve>
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }

    public function addElefe(Eleve $elefe): self
    {
        if (!$this->eleves->contains($elefe)) {
            $this->eleves[] = $elefe;
            $elefe->setParents($this);
        }

        return $this;
    }

    public function removeElefe(Eleve $elefe): self
    {
        if ($this->eleves->removeElement($elefe)) {
            // set the owning side to null (unless already changed)
            if ($elefe->getParents() === $this) {
                $elefe->setParents(null);
            }
        }

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
            $evaluation->setParents($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): self
    {
        if ($this->evaluation->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getParents() === $this) {
                $evaluation->setParents(null);
            }
        }

        return $this;
    }
}
