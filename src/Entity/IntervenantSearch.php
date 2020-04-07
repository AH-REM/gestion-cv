<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class IntervenantSearch
{
    private $nom;
    private $prenom;
    private $emploi;
    private $niveau;
    private $diplome;
    private $date;
    private $domaines;

    public function __construct()
    {
        $this->domaines = new ArrayCollection();
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmploi(): ?TypeEmploi
    {
        return $this->emploi;
    }

    public function setEmploi(?TypeEmploi $emploi): self
    {
        $this->emploi = $emploi;

        return $this;
    }

    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveau $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getDiplome(): ?Diplome
    {
        return $this->diplome;
    }

    public function setDiplome(?Diplome $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(?DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|Domaine[]
     */
    public function getDomaines(): Collection
    {
        return $this->domaines;
    }

    public function addDomaine(Domaine $domaine): self
    {
        if (!$this->domaines->contains($domaine)) {
            $this->domaines[] = $domaine;
        }

        return $this;
    }

    public function removeDomaine(Domaine $domaine): self
    {
        if ($this->domaines->contains($domaine)) {
            $this->domaines->removeElement($domaine);
        }

        return $this;
    }

}
