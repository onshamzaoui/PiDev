<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\DonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: DonRepository::class)]
class Don
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(1)]
    #[Assert\NotBlank]
    #[Assert\Range(min:1,max:50)]
    private ?int $quantitedon = null;

    #[ORM\ManyToOne]
    private ?TypeDechet $TypeDechet = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min:7,max:255)]
  #[Assert\Regex('/^[a-z]+$/i',message:"entrez une description valide")]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datedon = null;

   

    #[ORM\OneToOne(mappedBy: 'don', cascade: ['persist', 'remove'])]
    private ?Recyclage $recyclage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantitedon(): ?int
    {
        return $this->quantitedon;
    }

    public function setQuantitedon(int $quantitedon): self
    {
        $this->quantitedon = $quantitedon;

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

    public function getDatedon(): ?\DateTimeInterface
    {
        return $this->datedon;
    }

    public function setDatedon(\DateTimeInterface $datedon): self
    {
        $this->datedon = $datedon;

        return $this;
    }

    public function getTypeDechet(): ?TypeDechet
    {
        return $this->TypeDechet;
    }

    public function setTypeDechet(?TypeDechet $TypeDechet): self
    {
        $this->TypeDechet = $TypeDechet;

        return $this;
    }

    public function getRecyclage(): ?Recyclage
    {
        return $this->recyclage;
    }

    public function setRecyclage(?Recyclage $recyclage): self
    {
        // unset the owning side of the relation if necessary
        if ($recyclage === null && $this->recyclage !== null) {
            $this->recyclage->setDon(null);
        }

        // set the owning side of the relation if necessary
        if ($recyclage !== null && $recyclage->getDon() !== $this) {
            $recyclage->setDon($this);
        }

        $this->recyclage = $recyclage;

        return $this;
    }


     
     #[ORM\Column(type:"boolean")]
    public $estTraite;

    public function __construct()
    {
        $this->estTraite = false;
    }

    public function isEstTraite(): bool
    {
        return $this->estTraite;
    }

    public function setEstTraite(bool $estTraite): self
    {
        $this->estTraite = $estTraite;

        return $this;
    }

   
    public function __toString()
    {
        return " ID Don: " . $this->id . "\n \nQuantité du don: " . $this->quantitedon . "\n \nType du déchet:  " . $this->TypeDechet."\n \nStatut: " . $this->estTraite ;
        
    }


}
