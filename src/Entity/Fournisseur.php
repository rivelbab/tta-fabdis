<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FournisseurRepository")
 */
class Fournisseur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Modelimport", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $miCommerce;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Modelimport", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $miMedia;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trigramme;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $remise;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /*
     * @ORM\OneToMany(targetEntity="App\Entity\GroupeRemise", mappedBy="fournisseur", fetch="EAGER")
     *
    private $groupeRemises;
    */

    /*
     * @ORM\OneToMany(targetEntity="App\Entity\Upload", mappedBy="fournisseur", fetch="EAGER")
     *
    private $uploads;
    */

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        //$this->groupeRemises = new ArrayCollection();
        //$this->uploads = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMiCommerce(): ?Modelimport
    {
        return $this->miCommerce;
    }

    public function setMiCommerce(?Modelimport $miCommerce): self
    {
        $this->miCommerce = $miCommerce;

        return $this;
    }

    public function getMiMedia(): ?Modelimport
    {
        return $this->miMedia;
    }

    public function setMiMedia(?Modelimport $miMedia): self
    {
        $this->miMedia = $miMedia;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getTrigramme(): ?string
    {
        return $this->trigramme;
    }

    public function setTrigramme(?string $trigramme): self
    {
        $this->trigramme = $trigramme;

        return $this;
    }

    public function getRemise(): ?float
    {
        return $this->remise;
    }

    public function setRemise(?float $remise): self
    {
        $this->remise = $remise;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
}
