<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjetRepository")
 */
class Projet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $theme;

    /**
     * @ORM\Column(type="text")
     */
    private $deroule;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Domaine", inversedBy="projets")
     */
    private $domaine;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Branche", inversedBy="projets")
     */
    private $branche;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dateFin;

    public function __construct()
    {
        $this->domaine = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getDeroule(): ?string
    {
        return $this->deroule;
    }

    public function setDeroule(string $deroule): self
    {
        $this->deroule = $deroule;

        return $this;
    }

    /**
     * @return Collection|Domaine[]
     */
    public function getDomaine(): Collection
    {
        return $this->domaine;
    }

    public function addDomaine(Domaine $domaine): self
    {
        if (!$this->domaine->contains($domaine)) {
            $this->domaine[] = $domaine;
        }

        return $this;
    }

    public function removeDomaine(Domaine $domaine): self
    {
        if ($this->domaine->contains($domaine)) {
            $this->domaine->removeElement($domaine);
        }

        return $this;
    }

    public function getBranche(): ?Branche
    {
        return $this->branche;
    }

    public function setBranche(?Branche $branche): self
    {
        $this->branche = $branche;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDateDebut(): ?string
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?string $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?string
    {
        return $this->dateFin;
    }

    public function setDateFin(?string $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }
}
