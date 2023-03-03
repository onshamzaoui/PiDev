<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\TypeDechetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeDechetRepository::class)]
class TypeDechet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:4,max:255)]
    #[Assert\Regex('/^[a-z]+$/i',message:"entrez un nom valide")]
    private ?string $NomDechet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomDechet(): ?string
    {
        return $this->NomDechet;
    }

    public function setNomDechet(string $NomDechet): self
    {
        $this->NomDechet = $NomDechet;

        return $this;
    }


    public function __toString()
    {
        return $this->NomDechet;
    }

}
