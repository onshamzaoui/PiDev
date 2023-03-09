<?php

namespace App\Entity;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['emailUser'], message: 'There is already an account with this email_user')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email (message:"The Email '{{ value }}' is not a valid Email")]
    private ?string $emailUser = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"Name is required")]
    // #[Assert\Regex("/^[A-Z][a-z]/")]
    private ?string $nomUser = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:'Password is required')]
    #[Assert\Length(min:4)]
    private ?string $passwordUser = null;

    #[ORM\Column]
    private array $roleUser = [];

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:' Adresse is required')]
    private ?string $adresseUser = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true)]
    // #[Assert\NotBlank (message:"speciality is required")]
    private Speciality $speciality ;

    private $plainPassword;

    #[ORM\Column(nullable: true)]
    private ?bool $isVerified = null;

    #[ORM\Column(nullable: true)]
    private ?bool $disable = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numero = null;
    // private ?bool  $disable= false;
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Bonus::class, cascade:['persist'])]
    private Collection $bonuses;

    public function __construct()
    {
        $this->bonuses = new ArrayCollection();
    }
    /**
     * @return Collection<int, Bonus>
     */
    public function getBonuses(): Collection
    {
        return $this->bonuses;
    }

    public function addBonus(Bonus $bonus): self
    {
        if (!$this->bonuses->contains($bonus)) {
            $this->bonuses->add($bonus);
            $bonus->setUser($this);
        }

        return $this;
    }

    public function removeBonus(Bonus $bonus): self
    {
        if ($this->bonuses->removeElement($bonus)) {
            // set the owning side to null (unless already changed)
            if ($bonus->getUser() === $this) {
                $bonus->setUser(null);
            }
        }

        return $this;
    }


    
    #[ORM\Column(type:'integer')]

    public $points;
    public function __construct1()
    {
        $this->bonuses = new ArrayCollection();
        $this->points = $this->getPoints();
    }
    public function getPoints(): int
    {
        $points = 0;

        foreach ($this->bonuses as $bonus) {
            $points += $bonus->getPoints();
        }

        return $points;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmailUser(): ?string
    {
        return $this->emailUser;
    }

    public function setEmailUser(string $emailUser): self
    {
        $this->emailUser = $emailUser;

        return $this;
    }

    public function getNomUser(): ?string
    {
        return $this->nomUser;
    }

    public function setNomUser(string $nomUser): self
    {
        $this->nomUser = $nomUser;

        return $this;
    }

    public function getPasswordUser(): ?string
    {
        return $this->passwordUser;
    }
    public function getPassword(): string
    {
        return $this->passwordUser;
    }
    public function setPassword(string $password): void
{
    $this->passwordUser = password_hash($password, PASSWORD_DEFAULT);
}

    public function setPasswordUser(string $passwordUser): self
    {
        $this->passwordUser = $passwordUser;

        return $this;
    }

    public function getRoleUser(): array
    {
        //  return $this->roleUser;
         $roleUser = $this->roleUser;
          // guarantee every user at least has ROLE_USER
        // $roleUser[] = 'ROLE_CLIENT';

      
        // $roleUser[] = 'ROLE_PRO';
        // $roleUser[] = 'ROLE_ADMIN';

        return array_unique($roleUser);
    }

    public function setRoleUser(array $roleUser): self
    {
        $this->roleUser = $roleUser;

        return $this;
    }

    public function getAdresseUser(): ?string
    {
        return $this->adresseUser;
    }

    public function setAdresseUser(string $adresseUser): self
    {
        $this->adresseUser = $adresseUser;

        return $this;
    }
    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    public function getRoles(): array
    {
        return $this->roleUser;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;
    }

    // public function isVerified(): bool
    // {
    //     return $this->isVerified;
    // }

    // public function setIsVerified(bool $isVerified): self
    // {
    //     $this->isVerified = $isVerified;

    //     return $this;
    // }

    public function getSpeciality(): ?Speciality
    {
        return $this->speciality;
    }

    public function setSpeciality(?Speciality $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(?bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function isDisable(): ?bool
    {
        return $this->disable;
    }

    public function setDisable(?bool $disable): self
    {
        $this->disable = $disable;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

}
