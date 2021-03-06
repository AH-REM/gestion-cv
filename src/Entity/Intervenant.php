<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IntervenantRepository")
 * @UniqueEntity("mail")
 */
class Intervenant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 50
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 50
     * )
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(
     *      max = 100
     * )
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     * @Assert\Length(
     *      max = 6
     * )
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Length(
     *      max = 10
     * )
     */
    private $telFixe;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Length(
     *      max = 10
     * )
     */
    private $telPortable;

    /**
     * @ORM\Column(name="mail", type="string", length=100)
     * @Assert\NotBlank
     * @Assert\Email()
     * @Assert\Length(
     *      max = 100
     * )
     */
    private $mail;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $divers;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nameCv;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateMajCv;

    /**
     * @ORM\Column(type="date")
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Domaine", inversedBy="intervenants", cascade={"persist"})
     */
    private $domaines;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Diplome", inversedBy="intervenants")
     */
    private $diplome;

    private $niveau;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeEmploi", inversedBy="intervenants")
     */
    private $emploi;

    public function __construct()
    {
        $this->domaines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(?string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getTelFixe(): ?string
    {
        return $this->telFixe;
    }

    public function setTelFixe(?string $telFixe): self
    {
        $this->telFixe = $telFixe;

        return $this;
    }

    public function getTelPortable(): ?string
    {
        return $this->telPortable;
    }

    public function setTelPortable(?string $telPortable): self
    {
        $this->telPortable = $telPortable;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getDivers(): ?string
    {
        return $this->divers;
    }

    public function setDivers(?string $divers): self
    {
        $this->divers = $divers;

        return $this;
    }

    public function getNameCv(): ?string
    {
        return $this->nameCv;
    }

    public function setNameCv(?string $nameCv): self
    {
        $this->nameCv = $nameCv;

        return $this;
    }

    public function getDateMajCv(): ?\DateTimeInterface
    {
        return $this->dateMajCv;
    }

    public function setDateMajCv(?\DateTimeInterface $dateMajCv): self
    {
        $this->dateMajCv = $dateMajCv;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getDiplome(): ?Diplome
    {
        return $this->diplome;
    }

    public function setDiplome(?Diplome $diplome): self
    {
        $this->diplome = $diplome;

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

    public function getEmploi(): ?TypeEmploi
    {
        return $this->emploi;
    }

    public function setEmploi(?TypeEmploi $emploi): self
    {
        $this->emploi = $emploi;

        return $this;
    }

}
