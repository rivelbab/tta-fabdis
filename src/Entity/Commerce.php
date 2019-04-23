<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint as UniqueConstraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommerceRepository")
 * @ORM\Table(name="commerce",uniqueConstraints={@UniqueConstraint(name="unique_fournisseur",columns={"refciale", "fournisseur_id"})})
 * @Gedmo\Loggable
 */
class Commerce
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
    private $fabriquant;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gamme;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $refciale;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $refarticle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $refinfor;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gtin13;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $libelle30;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $libelle80;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $libelle240;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datetarif;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Gedmo\Versioned
     */
    private $tarif;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tarifd;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dev;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tva;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $qt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ub;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $qmc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mul;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $qmv;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $qmvt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $st;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $delai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $edi;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sta;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $datesta;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $refant;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $daterec;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $refe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $refnew;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $refold;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $douane;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hautp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hautpu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $largp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $largpu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profpu;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $poidsp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $poidspu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $diam;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $diamu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sect;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sectu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vpu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mkt1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mkt1l;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mkt2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mkt2l;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mkt3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mkt3l;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mkt4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mkt4l;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mkt5;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mkt5l;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fam1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fam1l;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fam2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fam2l;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fam3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fam3l;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dlsr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $made;

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

    public function getFabriquant(): ?string
    {
        return $this->fabriquant;
    }

    public function setFabriquant(?string $fabriquant): self
    {
        $this->fabriquant = $fabriquant;

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

    public function getGamme(): ?string
    {
        return $this->gamme;
    }

    public function setGamme(?string $gamme): self
    {
        $this->gamme = $gamme;

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

    public function getRefarticle(): ?string
    {
        return $this->refarticle;
    }

    public function setRefarticle(?string $refarticle): self
    {
        $this->refarticle = $refarticle;

        return $this;
    }

    public function getRefinfor(): ?string
    {
        return $this->refinfor;
    }

    public function setRefinfor(?string $refinfor): self
    {
        $this->refinfor = $refinfor;

        return $this;
    }

    public function getGtin13(): ?string
    {
        return $this->gtin13;
    }

    public function setGtin13(?string $gtin13): self
    {
        $this->gtin13 = $gtin13;

        return $this;
    }

    public function getLibelle30(): ?string
    {
        return $this->libelle30;
    }

    public function setLibelle30(?string $libelle30): self
    {
        $this->libelle30 = $libelle30;

        return $this;
    }

    public function getLibelle80(): ?string
    {
        return $this->libelle80;
    }

    public function setLibelle80(?string $libelle80): self
    {
        $this->libelle80 = $libelle80;

        return $this;
    }

    public function getLibelle240(): ?string
    {
        return $this->libelle240;
    }

    public function setLibelle240(?string $libelle240): self
    {
        $this->libelle240 = $libelle240;

        return $this;
    }

    public function getDatetarif(): ?\DateTimeInterface
    {
        return $this->datetarif;
    }

    public function setDatetarif(?\DateTimeInterface $datetarif): self
    {
        $this->datetarif = $datetarif;

        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(?float $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getTarifd(): ?float
    {
        return $this->tarifd;
    }

    public function setTarifd(?float $tarifd): self
    {
        $this->tarifd = $tarifd;

        return $this;
    }

    public function getDev(): ?string
    {
        return $this->dev;
    }

    public function setDev(?string $dev): self
    {
        $this->dev = $dev;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(?float $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function getQt(): ?int
    {
        return $this->qt;
    }

    public function setQt(?int $qt): self
    {
        $this->qt = $qt;

        return $this;
    }

    public function getUb(): ?string
    {
        return $this->ub;
    }

    public function setUb(?string $ub): self
    {
        $this->ub = $ub;

        return $this;
    }

    public function getQmc(): ?int
    {
        return $this->qmc;
    }

    public function setQmc(?int $qmc): self
    {
        $this->qmc = $qmc;

        return $this;
    }

    public function getMul(): ?int
    {
        return $this->mul;
    }

    public function setMul(?int $mul): self
    {
        $this->mul = $mul;

        return $this;
    }

    public function getQmv(): ?int
    {
        return $this->qmv;
    }

    public function setQmv(?int $qmv): self
    {
        $this->qmv = $qmv;

        return $this;
    }

    public function getQmvt(): ?string
    {
        return $this->qmvt;
    }

    public function setQmvt(?string $qmvt): self
    {
        $this->qmvt = $qmvt;

        return $this;
    }

    public function getSt(): ?int
    {
        return $this->st;
    }

    public function setSt(?int $st): self
    {
        $this->st = $st;

        return $this;
    }

    public function getDelai(): ?string
    {
        return $this->delai;
    }

    public function setDelai(?string $delai): self
    {
        $this->delai = $delai;

        return $this;
    }

    public function getEdi(): ?int
    {
        return $this->edi;
    }

    public function setEdi(?int $edi): self
    {
        $this->edi = $edi;

        return $this;
    }

    public function getDug(): ?string
    {
        return $this->dug;
    }

    public function setDug(?string $dug): self
    {
        $this->dug = $dug;

        return $this;
    }

    public function getSta(): ?string
    {
        return $this->sta;
    }

    public function setSta(?string $sta): self
    {
        $this->sta = $sta;

        return $this;
    }

    public function getDatesta(): ?string
    {
        return $this->datesta;
    }

    public function setDatesta(?string $datesta): self
    {
        $this->datesta = $datesta;

        return $this;
    }

    public function getRefant(): ?string
    {
        return $this->refant;
    }

    public function setRefant(?string $refant): self
    {
        $this->refant = $refant;

        return $this;
    }

    public function getDaterec(): ?string
    {
        return $this->daterec;
    }

    public function setDaterec(?string $daterec): self
    {
        $this->daterec = $daterec;

        return $this;
    }

    public function getRefe(): ?string
    {
        return $this->refe;
    }

    public function setRefe(?string $refe): self
    {
        $this->refe = $refe;

        return $this;
    }

    public function getRefnew(): ?string
    {
        return $this->refnew;
    }

    public function setRefnew(?string $refnew): self
    {
        $this->refnew = $refnew;

        return $this;
    }

    public function getRefold(): ?string
    {
        return $this->refold;
    }

    public function setRefold(?string $refold): self
    {
        $this->refold = $refold;

        return $this;
    }

    public function getDouane(): ?string
    {
        return $this->douane;
    }

    public function setDouane(?string $douane): self
    {
        $this->douane = $douane;

        return $this;
    }

    public function getHautp(): ?string
    {
        return $this->hautp;
    }

    public function setHautp(?string $hautp): self
    {
        $this->hautp = $hautp;

        return $this;
    }

    public function getHautpu(): ?string
    {
        return $this->hautpu;
    }

    public function setHautpu(?string $hautpu): self
    {
        $this->hautpu = $hautpu;

        return $this;
    }

    public function getLargp(): ?string
    {
        return $this->largp;
    }

    public function setLargp(?string $largp): self
    {
        $this->largp = $largp;

        return $this;
    }

    public function getLargpu(): ?string
    {
        return $this->largpu;
    }

    public function setLargpu(?string $largpu): self
    {
        $this->largpu = $largpu;

        return $this;
    }

    public function getProfp(): ?string
    {
        return $this->profp;
    }

    public function setProfp(?string $profp): self
    {
        $this->profp = $profp;

        return $this;
    }

    public function getProfpu(): ?string
    {
        return $this->profpu;
    }

    public function setProfpu(?string $profpu): self
    {
        $this->profpu = $profpu;

        return $this;
    }

    public function getPoidsp(): ?float
    {
        return $this->poidsp;
    }

    public function setPoidsp(?float $poidsp): self
    {
        $this->poidsp = $poidsp;

        return $this;
    }

    public function getPoidspu(): ?string
    {
        return $this->poidspu;
    }

    public function setPoidspu(?string $poidspu): self
    {
        $this->poidspu = $poidspu;

        return $this;
    }

    public function getDiam(): ?string
    {
        return $this->diam;
    }

    public function setDiam(?string $diam): self
    {
        $this->diam = $diam;

        return $this;
    }

    public function getDiamu(): ?string
    {
        return $this->diamu;
    }

    public function setDiamu(?string $diamu): self
    {
        $this->diamu = $diamu;

        return $this;
    }

    public function getSect(): ?string
    {
        return $this->sect;
    }

    public function setSect(?string $sect): self
    {
        $this->sect = $sect;

        return $this;
    }

    public function getSectu(): ?string
    {
        return $this->sectu;
    }

    public function setSectu(?string $sectu): self
    {
        $this->sectu = $sectu;

        return $this;
    }

    public function getVp(): ?string
    {
        return $this->vp;
    }

    public function setVp(?string $vp): self
    {
        $this->vp = $vp;

        return $this;
    }

    public function getVpu(): ?string
    {
        return $this->vpu;
    }

    public function setVpu(?string $vpu): self
    {
        $this->vpu = $vpu;

        return $this;
    }

    public function getMkt1(): ?string
    {
        return $this->mkt1;
    }

    public function setMkt1(?string $mkt1): self
    {
        $this->mkt1 = $mkt1;

        return $this;
    }

    public function getMkt1l(): ?string
    {
        return $this->mkt1l;
    }

    public function setMkt1l(?string $mkt1l): self
    {
        $this->mkt1l = $mkt1l;

        return $this;
    }

    public function getMkt2(): ?string
    {
        return $this->mkt2;
    }

    public function setMkt2(?string $mkt2): self
    {
        $this->mkt2 = $mkt2;

        return $this;
    }

    public function getMkt2l(): ?string
    {
        return $this->mkt2l;
    }

    public function setMkt2l(?string $mkt2l): self
    {
        $this->mkt2l = $mkt2l;

        return $this;
    }

    public function getMkt3(): ?string
    {
        return $this->mkt3;
    }

    public function setMkt3(?string $mkt3): self
    {
        $this->mkt3 = $mkt3;

        return $this;
    }

    public function getMkt3l(): ?string
    {
        return $this->mkt3l;
    }

    public function setMkt3l(?string $mkt3l): self
    {
        $this->mkt3l = $mkt3l;

        return $this;
    }

    public function getMkt4(): ?string
    {
        return $this->mkt4;
    }

    public function setMkt4(?string $mkt4): self
    {
        $this->mkt4 = $mkt4;

        return $this;
    }

    public function getMkt4l(): ?string
    {
        return $this->mkt4l;
    }

    public function setMkt4l(?string $mkt4l): self
    {
        $this->mkt4l = $mkt4l;

        return $this;
    }

    public function getMkt5(): ?string
    {
        return $this->mkt5;
    }

    public function setMkt5(?string $mkt5): self
    {
        $this->mkt5 = $mkt5;

        return $this;
    }

    public function getMkt5l(): ?string
    {
        return $this->mkt5l;
    }

    public function setMkt5l(?string $mkt5l): self
    {
        $this->mkt5l = $mkt5l;

        return $this;
    }

    public function getFam1(): ?string
    {
        return $this->fam1;
    }

    public function setFam1(?string $fam1): self
    {
        $this->fam1 = $fam1;

        return $this;
    }

    public function getFam1l(): ?string
    {
        return $this->fam1l;
    }

    public function setFam1l(?string $fam1l): self
    {
        $this->fam1l = $fam1l;

        return $this;
    }

    public function getFam2(): ?string
    {
        return $this->fam2;
    }

    public function setFam2(?string $fam2): self
    {
        $this->fam2 = $fam2;

        return $this;
    }

    public function getFam2l(): ?string
    {
        return $this->fam2l;
    }

    public function setFam2l(?string $fam2l): self
    {
        $this->fam2l = $fam2l;

        return $this;
    }

    public function getFam3(): ?string
    {
        return $this->fam3;
    }

    public function setFam3(?string $fam3): self
    {
        $this->fam3 = $fam3;

        return $this;
    }

    public function getFam3l(): ?string
    {
        return $this->fam3l;
    }

    public function setFam3l(?string $fam3l): self
    {
        $this->fam3l = $fam3l;

        return $this;
    }

    public function getDlsr(): ?string
    {
        return $this->dlsr;
    }

    public function setDlsr(?string $dlsr): self
    {
        $this->dlsr = $dlsr;

        return $this;
    }

    public function getMade(): ?string
    {
        return $this->made;
    }

    public function setMade(?string $made): self
    {
        $this->made = $made;

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
