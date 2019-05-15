<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CodebarreRepository")
 */
class Codebarre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $item;

     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $marque;

    // ==== stock le fournisseur choisi afin de recuperer son id dans fournisseurId ==
    private $selectedFr;

    /**
     * @ORM\Column(type="integer")
     */
    private $fournisseurId;

      /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ignorer;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItem(): ?string
    {
        return $this->item;
    }

    public function setItem(?string $item): self
    {
        $this->item = $item;

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

    
    public function getFournisseurId(): ?int
    {
        return $this->fournisseurId;
    }

    public function setFournisseurId(?int $fournisseurId): self
    {
        $this->fournisseurId = $fournisseurId;

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
}
