<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @UniqueEntity(fields="code")
 */
class Article
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
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $groupe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $famille;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fournisseur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ean;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $regroupement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $decline;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $web;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stock;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $textlong;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $textcourt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $garantie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $refcommande;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prixvente;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prixachat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $remiseachat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reffournisseur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $designationfr;

     /**
     * @ORM\Column(type="integer")
     */
    private $fournisseurId;

     /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

     /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $poids;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getGroupe(): ?string
    {
        return $this->groupe;
    }

    public function setGroupe(?string $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function getFamille(): ?string
    {
        return $this->famille;
    }

    public function setFamille(?string $famille): self
    {
        $this->famille = $famille;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFournisseur(): ?string
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?string $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

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

    public function getEan(): ?string
    {
        return $this->ean;
    }

    public function setEan(?string $ean): self
    {
        $this->ean = $ean;

        return $this;
    }

    public function getRegroupement(): ?string
    {
        return $this->regroupement;
    }

    public function setRegroupement(?string $regroupement): self
    {
        $this->regroupement = $regroupement;

        return $this;
    }

    public function getDecline(): ?int
    {
        return $this->decline;
    }

    public function setDecline(?int $decline): self
    {
        $this->decline = $decline;

        return $this;
    }

    public function getWeb(): ?int
    {
        return $this->web;
    }

    public function setWeb(?int $web): self
    {
        $this->web = $web;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getTextlong(): ?string
    {
        return $this->textlong;
    }

    public function setTextlong(?string $textlong): self
    {
        $this->textlong = $textlong;

        return $this;
    }

    public function getTextcourt(): ?string
    {
        return $this->textcourt;
    }

    public function setTextcourt(?string $textcourt): self
    {
        $this->textcourt = $textcourt;

        return $this;
    }

    public function getGarantie(): ?int
    {
        return $this->garantie;
    }

    public function setGarantie(?int $garantie): self
    {
        $this->garantie = $garantie;

        return $this;
    }

    public function getRefcommande(): ?string
    {
        return $this->refcommande;
    }

    public function setRefcommande(?string $refcommande): self
    {
        $this->refcommande = $refcommande;

        return $this;
    }

    public function getPrixvente(): ?float
    {
        return $this->prixvente;
    }

    public function setPrixvente(?float $prixvente): self
    {
        $this->prixvente = $prixvente;

        return $this;
    }

    public function getPrixachat(): ?float
    {
        return $this->prixachat;
    }

    public function setPrixachat(?float $prixachat): self
    {
        $this->prixachat = $prixachat;

        return $this;
    }

    public function getRemiseachat(): ?float
    {
        return $this->remiseachat;
    }

    public function setRemiseachat(?float $remiseachat): self
    {
        $this->remiseachat = $remiseachat;

        return $this;
    }

    public function getReffournisseur(): ?string
    {
        return $this->reffournisseur;
    }

    public function setReffournisseur(?string $reffournisseur): self
    {
        $this->reffournisseur = $reffournisseur;

        return $this;
    }

    public function getDesignationfr(): ?string
    {
        return $this->designationfr;
    }

    public function setDesignationfr(?string $designationfr): self
    {
        $this->designationfr = $designationfr;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
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

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(?float $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function __set($name, $value)
    {
        $value2 = $value;

        $function = "set" . ucfirst($name);
        if ($name == 'remise' || $name == 'prixVente' || $name == 'poids') {
            $value2 = floatval($value2);
        }
        return $this->$function($value2);
    }

    public function __get($name)
    {
        $function = "get" . ucfirst($name);
        return $this->$function();
    }
}
