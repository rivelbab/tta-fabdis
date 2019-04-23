<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 */
class Media
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $fournisseurId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $refciale;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typm;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $num;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codval;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ignorer;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    // ==== stock le fournisseur choisi afin de recuperer son id dans fournisseurId ==
    private $selectedFr;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFournisseurId(): ?int
    {
        return $this->fournisseurId;
    }

    public function setFournisseurId(?int $fournisseurId): self
    {
        $this->fournisseurId = $fournisseurId;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getRefciale(): ?string
    {
        return $this->refciale;
    }

    public function setRefciale(?string $refciale): self
    {
        $this->refciale = $refciale;

        return $this;
    }

    public function getTypm(): ?string
    {
        return $this->typm;
    }

    public function setTypm(?string $typm): self
    {
        $this->typm = $typm;

        return $this;
    }

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function setNum(?string $num): self
    {
        $this->num = $num;

        return $this;
    }

    public function getCodval(): ?string
    {
        return $this->codval;
    }

    public function setCodval(?string $codval): self
    {
        $this->codval = $codval;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrlt(): ?string
    {
        return $this->urlt;
    }

    public function setUrlt(?string $urlt): self
    {
        $this->urlt = $urlt;

        return $this;
    }
    public function getIgnorer(): ?string
    {
        return $this->ignorer;
    }

    public function setIgnorer(?string $ignorer): self
    {
        $this->ignorer = $ignorer;

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

    public function getSelectedFr(): ?Fournisseur
    {
        return $this->selectedFr;
    }
    public function setSelectedFr(?Fournisseur $selectedFr): self
    {
        $this->selectedFr = $selectedFr;

        return $this;
    }

    public function __set($name, $value)
    {
        $value2 = $value;

        $function = "set".ucfirst($name);
        
        return $this->$function($value2);
    }

    public function __get($name)
    {
        $function = "get".ucfirst($name);
        return $this->$function();
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
