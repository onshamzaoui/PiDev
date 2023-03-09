<?php

namespace App\Entity;

use App\Repository\AffectationServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateType;


#[ORM\Entity(repositoryClass: AffectationServiceRepository::class)]
class AffectationService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message:"Votre nom de service est vide")]
    private ?Service $NomService = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"Votre date de service est vide")]
    /**
     * @Assert\Range(
     *      min = "first day of January UTC",
     *      max = "first day of March next year UTC"
     * )
     */
    private ?\DateTimeInterface $DateService = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]  
    // /**
    // * @Assert\Range(
    // *      min = "08:00",
    // *      max = "15:00"
    // * )
    // */
    private ?\DateTimeInterface $HeureService = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Votre nom de service est vide")]
    #[Assert\Regex(
        pattern: '/^[a-z]+$/i',
        htmlPattern: '^[a-zA-Z]+$'
    )]
    /**
    * @Assert\Type("string")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" Entrer un nom de service au mini de 5 caracteres"
     *
     *     )
     */
    private ?string $LieuxService = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomService(): ?Service
    {
        return $this->NomService;
    }

    public function setNomService(Service $NomService): self
    {
        $this->NomService = $NomService;

        return $this;
    }

    public function getDateService(): ?\DateTimeInterface
    {
        return $this->DateService;
    }

    public function setDateService(\DateTimeInterface $DateService): self
    {
        $this->DateService = $DateService;

        return $this;
    }

    public function getHeureService(): ?\DateTimeInterface
    {
        return $this->HeureService;
    }

    public function setHeureService(\DateTimeInterface $HeureService): self
    {
        $this->HeureService = $HeureService;

        return $this;
    }

    public function getLieuxService(): ?string
    {
        return $this->LieuxService;
    }

    public function setLieuxService(string $LieuxService): self
    {
        $this->LieuxService = $LieuxService;

        return $this;
    }
}
