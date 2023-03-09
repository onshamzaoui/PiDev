<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: '/^[a-z]+$/i',
        htmlPattern: '^[a-zA-Z]+$'
    )]
    #[Assert\NotBlank(message:"Votre nom de service est vide")]
     /**
    * @Assert\Type("string")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" Entrer un nom de service au mini de 5 caracteres"
     *
     *     )
     */
    private ?string $NomService = null;

    // #[ORM\Column(length: 255)]
    // #[Assert\NotBlank(message:"Votre nom de service est vide")]
    // private ?string $TypeService = null;

    // #[ORM\Column(length: 255)]
    // private ?string $EtatService = null;

    // #[ORM\Column]
    // #[Assert\Positive(message:"le prix doit etre positif")]
   
     /**
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    /**
     * @Assert\Range(
     *      min = 10.00,
     *      max = 500.00,
     *      notInRangeMessage = "le prix doit etre entre {{ min }}TND et {{ max }}TND",
     * )
     */
    private ?float $PrixService = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Votre image est vide")]
    //  /**
    //  * @Assert\Image(
    //  *     minWidth = 200,
    //  *     maxWidth = 4000,
    //  *     minHeight = 200,
    //  *     maxHeight = 4000
    //  * )
    //  */
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"Votre nom de service est vide")]

    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomService(): ?string
    {
        return $this->NomService;
    }

    public function setNomService(string $NomService): self
    {
        $this->NomService = $NomService;

        return $this;
    }

    public function getTypeService(): ?string
    {
        return $this->TypeService;
    }

    public function setTypeService(string $TypeService): self
    {
        $this->TypeService = $TypeService;

        return $this;
    }

    public function getEtatService(): ?string
    {
        return $this->EtatService;
    }

    public function setEtatService(string $EtatService): self
    {
        $this->EtatService = $EtatService;

        return $this;
    }

    public function getPrixService(): ?float
    {
        return $this->PrixService;
    }

    public function setPrixService(float $PrixService): self
    {
        $this->PrixService = $PrixService;

        return $this;
    }
    public function __toString()
    {
        return $this->NomService;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
