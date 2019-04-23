<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModelimportRepository")
 */
class Modelimport
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
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $fileHeader;

    /**
     * @ORM\Column(type="text")
     */
    private $fields;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /*
     * @ORM\OneToMany(targetEntity="App\Entity\Fournisseur", mappedBy="modelimport", fetch="EXTRA_LAZY")
     *
    private $fournisseurs;
    */

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        //$this->fournisseurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFileHeader(): ?string
    {
        return $this->fileHeader;
    }

    public function setFileHeader(string $fileHeader): self
    {
        $this->fileHeader = $fileHeader;

        return $this;
    }

    public function getFields(): ?string
    {
        return $this->fields;
    }

    public function setFields(string $fields): self
    {
        $this->fields = $fields;

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

    /*
     * @return Collection|Fournisseur[]
     *
    public function getFournisseurs(): Collection
    {
        return $this->fournisseurs;
    }

    public function addFournisseur(Fournisseur $fournisseur): self
    {
        if (!$this->fournisseurs->contains($fournisseur)) {
            $this->fournisseurs[] = $fournisseur;
            $fournisseur->setModelimport($this);
        }

        return $this;
    }

    public function removeFournisseur(Fournisseur $fournisseur): self
    {
        if ($this->fournisseurs->contains($fournisseur)) {
            $this->fournisseurs->removeElement($fournisseur);
            // set the owning side to null (unless already changed)
            if ($fournisseur->getModelimport() === $this) {
                $fournisseur->setModelimport(null);
            }
        }

        return $this;
    }
    */
}
