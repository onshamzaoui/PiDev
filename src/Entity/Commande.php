<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'L\'adresse de la commande est obligatoire.')]
    #[Assert\Length(max: 255, maxMessage: 'L\'adresse de la commande ne peut pas dépasser {{ limit }} caractères.')]
    private ?string $adressecommande = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull(message: 'La date de commande est obligatoire.')]
    private ?\DateTimeInterface $datecommande = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: Produit::class)]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    // #[ORM\OneToMany(mappedBy: 'commande', targetEntity: Produit::class)]
    // private Collection $produits;

    // public function __construct()
    // {
    //     $this->produits = new ArrayCollection();
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdressecommande(): ?string
    {
        return $this->adressecommande;
    }

    public function setAdressecommande(string $adressecommande): self
    {
        $this->adressecommande = $adressecommande;

        return $this;
    }

    public function getDatecommande(): ?\DateTimeInterface
    {
        return $this->datecommande;
    }

    public function setDatecommande(\DateTimeInterface $datecommande): self
    {
        $this->datecommande = $datecommande;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    // /**
    //  * @return Collection<int, Produit>
    //  */
    // public function getProduits(): Collection
    // {
    //     return $this->produits;
    // }

    // public function addProduit(Produit $produit): self
    // {
    //     if (!$this->produits->contains($produit)) {
    //         $this->produits->add($produit);
    //         $produit->setCommande($this);
    //     }

    //     return $this;
    // }

    // public function removeProduit(Produit $produit): self
    // {
    //     if ($this->produits->removeElement($produit)) {
    //         // set the owning side to null (unless already changed)
    //         if ($produit->getCommande() === $this) {
    //             $produit->setCommande(null);
    //         }
    //     }

    //     return $this;
    // }
    public function __toString(): string
{
    return sprintf('Commande n°%d', $this->getId());
}

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setCommande($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCommande() === $this) {
                $produit->setCommande(null);
            }
        }

        return $this;
    }

}
