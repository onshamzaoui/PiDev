<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomcategorie = null;

    #[ORM\OneToMany(mappedBy: 'nomcategorie', targetEntity: Produit::class)]
    private Collection $nomproduit;

    public function __construct()
    {
        $this->nomproduit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomcategorie(): ?string
    {
        return $this->nomcategorie;
    }

    public function setNomcategorie(string $nomcategorie): self
    {
        $this->nomcategorie = $nomcategorie;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getNomproduit(): Collection
    {
        return $this->nomproduit;
    }

    public function addNomproduit(Produit $nomproduit): self
    {
        if (!$this->nomproduit->contains($nomproduit)) {
            $this->nomproduit->add($nomproduit);
            $nomproduit->setNomcategorie($this);
        }

        return $this;
    }

    public function removeNomproduit(Produit $nomproduit): self
    {
        if ($this->nomproduit->removeElement($nomproduit)) {
            // set the owning side to null (unless already changed)
            if ($nomproduit->getNomcategorie() === $this) {
                $nomproduit->setNomcategorie(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nomcategorie; // assuming that the name property contains the name of the category
    }
}
