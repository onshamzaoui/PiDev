<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Assert\NotBlank;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Nom_produit is required")]

    #[Assert\Length(min: 4)]
    private ?string $nomproduit = null;

    #[ORM\Column]
    #[Assert\Positive(message: "le prix doit etre positif")]
    #[Assert\NotBlank(message: "prix is required")]
    /**
     * @Assert\Range(
     *      min = 10.00,
     *      max = 1000.00,
     *      notInRangeMessage = "le prix doit etre entre {{ min }}$ et {{ max }}$",
     * )
     */
    private ?float $prixproduit = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message: "le prix doit etre positif")]
    /**
     * @Assert\Range(
     *      min = 5.00,
     *      max = 1000.00,
     *      notInRangeMessage = "le prix doit etre entre {{ min }}$ et {{ max }}$",
     * )
     */
    private ?float $prixremise = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message: "La quantité doit être positive")]
    #[Assert\NotBlank(message: "La quantité est obligatoire")]
    #[Assert\Type(type: 'integer', message: "La quantité doit être un nombre entier")]
    #[Assert\Range(min: 1, max: 150, minMessage: "La quantité doit être supérieure ou égale à 1", maxMessage: "La quantité ne doit pas dépasser 100000")]
    private ?int $quantiteproduit = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'nomproduit')]
    private ?Categorie $nomcategorie = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Commande $commande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomproduit(): ?string
    {
        return $this->nomproduit;
    }

    public function setNomproduit(string $nomproduit): self
    {
        $this->nomproduit = $nomproduit;

        return $this;
    }

    public function getPrixproduit(): ?float
    {
        return $this->prixproduit;
    }

    public function setPrixproduit(float $prixproduit): self
    {
        $this->prixproduit = $prixproduit;

        return $this;
    }

    public function getPrixremise(): ?float
    {
        return $this->prixremise;
    }

    public function setPrixremise(?float $prixremise): self
    {
        $this->prixremise = $prixremise;

        return $this;
    }

    public function getQuantiteproduit(): ?int
    {
        return $this->quantiteproduit;
    }

    public function setQuantiteproduit(?int $quantiteproduit): self
    {
        $this->quantiteproduit = $quantiteproduit;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getNomcategorie(): ?Categorie
    {
        return $this->nomcategorie;
    }

    public function setNomcategorie(?Categorie $nomcategorie): self
    {
        $this->nomcategorie = $nomcategorie;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }
}
