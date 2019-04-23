<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UploadRepository")
 * @Vich\Uploadable
 */
class Upload
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="upload_tarif", fileNameProperty="tarifName", size="tarifSize")
     *
     * @Assert\File(mimeTypes={ "application/vnd.ms-excel","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" })
     *
     * @var File
     */
    private $tarifFile;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $tarifName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var integer
     */
    private $tarifSize;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fournisseur", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fournisseur;

    /**
    * NOTE: 0 = Echec , 1 = INTERROMPU et 2 = SUCCES
     * @ORM\Column(type="integer", nullable=true)
     */
    private $statut ;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isMedia = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    public function __construct()
   {
       $this->createdAt = new \DateTime("now");
   }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $tarif
     */
    public function setTarifFile(?File $tarif = null): void
    {
        $this->tarifFile = $tarif;

        if (null !== $tarif) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getTarifFile(): ?File
    {
        return $this->tarifFile;
    }

    public function getTarifName(): ?string
    {
        return $this->tarifName;
    }

    public function setTarifName(?string $tarifName): void
    {
        $this->tarifName = $tarifName;
    }

    public function getTarifSize(): ?int
    {
        return $this->tarifSize;
    }

    public function setTarifSize(?int $tarifSize): void
    {
        $this->tarifSize = $tarifSize;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(?int $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getIsMedia(): ?bool
    {
        return $this->isMedia;
    }

    public function setIsMedia(?bool $isMedia): self
    {
        $this->isMedia = $isMedia;

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
}
