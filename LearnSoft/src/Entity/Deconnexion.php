<?php

namespace App\Entity;

use App\Repository\DeconnexionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeconnexionRepository::class)]
class Deconnexion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $adresseIp;

    #[ORM\Column(type: 'boolean')]
    private $status;

    #[ORM\Column(type: 'string', length: 255)]
    private $tempsMis;

    #[ORM\Column(type: 'string', length: 255)]
    private $localisation;

    #[ORM\Column(type: 'string', length: 255)]
    private $device;

    #[ORM\ManyToOne(targetEntity: Eleve::class, inversedBy: 'deconnexion')]
    #[ORM\JoinColumn(nullable: false)]
    private $eleve;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresseIp(): ?string
    {
        return $this->adresseIp;
    }

    public function setAdresseIp(string $adresseIp): self
    {
        $this->adresseIp = $adresseIp;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

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

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getDevice(): ?string
    {
        return $this->device;
    }

    public function setDevice(string $device): self
    {
        $this->device = $device;

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
}
