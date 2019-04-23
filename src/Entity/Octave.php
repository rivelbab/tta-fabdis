<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OctaveRepository")
 * @UniqueEntity(fields="code")
 * @Gedmo\Loggable
 */
class Octave
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $refFournisseur;

    /**
     * @ORM\Column(type="integer")
     */
    private $fournisseurId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $refCommande;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="float", nullable=true)
     */
    private $prixAchat;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="float", nullable=true)
     */
    private $prixVente;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="float", nullable=true)
     */
    private $prixTSQ;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="float", nullable=true)
     */
    private $remiseTSQ;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="float", nullable=true)
     */
    private $remise;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $marque;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $poids;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codebarre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hscode;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $libelle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ignorer;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $etat = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $isSpecial = 0;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    // ==== stock le fournisseur choisi afin de recuperer son id dans fournisseurId ==
    private $selectedFr;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="float", nullable=true)
     */
    private $prixVenteTSQ;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

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

    public function getRefFournisseur(): ?string
    {
        return $this->refFournisseur;
    }

    public function setRefFournisseur(?string $refFournisseur): self
    {
        $this->refFournisseur = $refFournisseur;

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

    public function getRefCommande(): ?string
    {
        return $this->refCommande;
    }

    public function setRefCommande(?string $refCommande): self
    {
        $this->refCommande = $refCommande;

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

    public function getPrixAchat(): ?float
    {
        return $this->prixAchat;
    }

    public function setPrixAchat(?float $prixAchat): self
    {
        $this->prixAchat = $prixAchat;

        return $this;
    }

    public function getPrixVente(): ?float
    {
        return $this->prixVente;
    }

    public function setPrixVente(?float $prixVente): self
    {
        $this->prixVente = $prixVente;

        return $this;
    }

    public function getPrixTSQ(): ?float
    {
        return $this->prixTSQ;
    }

    public function setPrixTSQ(?float $prixTSQ): self
    {
        $this->$prixTSQ = $prixTSQ;

        return $this;
    }

    public function getRemiseTSQ(): ?float
    {
        return $this->remiseTSQ;
    }

    public function setRemiseTSQ(?float $remiseTSQ): self
    {
        $this->$remiseTSQ = $remiseTSQ;

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

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;

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

    public function getCodebarre(): ?string
    {
        return $this->codebarre;
    }

    public function setCodebarre(?string $codebarre): self
    {
        $this->codebarre = $codebarre;

        return $this;
    }

    public function getHscode(): ?string
    {
        return $this->hscode;
    }

    public function setHscode(?string $hscode): self
    {
        $this->hscode = $hscode;

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

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

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

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(?int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getIsSpecial(): ?int
    {
        return $this->isSpecial;
    }

    public function setIsSpecial(?int $isSpecial): self
    {
        $this->isSpecial = $isSpecial;

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

        $function = "set" . ucfirst($name);
        if ($name == 'remise' || $name == 'prixVente' || $name == 'poids' || $name == 'prixTSQ') {
            $value2 = floatval($value2);
        }
        return $this->$function($value2);
    }

    public function __get($name)
    {
        $function = "get" . ucfirst($name);
        return $this->$function();
    }

    public function getPrixVenteTSQ(): ?float
    {
        return $this->prixVenteTSQ;
    }

    public function setPrixVenteTSQ(?float $prixVenteTSQ): self
    {
        $this->prixVenteTSQ = $prixVenteTSQ;

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
}
