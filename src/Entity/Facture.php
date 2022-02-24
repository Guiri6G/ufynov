<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 */
class Facture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("facture:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
     * @Assert\NotBlank()
     * @Assert\Regex("/^\d{3}$/")
     * @Groups("facture:read")
     */
    private $refClient;

    /**
     * @ORM\Column(type="date")
     * @Groups("facture:read")
     */
    private $dateEmission;

    /**
     * @ORM\Column(type="integer")
     * @Groups("facture:read")
     */
    private $statutPaiement;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups("facture:read")
     */
    private $datePaiement;

    /**
     * @ORM\Column(type="integer")
     * @Groups("facture:read")
     */
    public $prixTotal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefClient(): ?string
    {
        return $this->refClient;
    }

    public function setRefClient(string $refClient): self
    {
        $this->refClient = $refClient;

        return $this;
    }

    public function getDateEmission(): ?\DateTimeInterface
    {
        return $this->dateEmission;
    }

    public function setDateEmission(\DateTimeInterface $dateEmission): self
    {
        $this->dateEmission = $dateEmission;

        return $this;
    }

    public function getStatutPaiement(): ?int
    {
        return $this->statutPaiement;
    }

    public function setStatutPaiement(int $statutPaiement): self
    {
        $this->statutPaiement = $statutPaiement;

        return $this;
    }

    public function getDatePaiement(): ?\DateTimeInterface
    {
        return $this->datePaiement;
    }

    public function setDatePaiement(?\DateTimeInterface $datePaiement): self
    {
        $this->datePaiement = $datePaiement;

        return $this;
    }

    public function getPrixTotal(): ?int
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(int $prixTotal): self
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }
}
