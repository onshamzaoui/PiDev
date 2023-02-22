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
#[UniqueEntity(fields: ['email_user'], message: 'There is already an account with this email_user')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email (message:"The Email '{{ value }}' is not a valid Email")]
    private ?string $email_user = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"Name is required")]
    // #[Assert\Regex("/^[A-Z][a-z]/")]
    private ?string $nom_user = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:'Password is required')]
    #[Assert\Length(min:4)]
    
    private ?string $password_user = null;

    #[ORM\Column]
    private array $role_user = [];

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:' Adresse is required')]
    private ?string $adresse_user = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true)]
    // #[Assert\NotBlank (message:"speciality is required")]
    private Speciality $speciality ;

   
    // #[ORM\ManyToOne(inversedBy: 'user')]

    // // #[ORM\ManyToOne(mappedBy: 'user', targetEntity: Specialite::class)]
    // #[Assert\NotBlank (message:'Specialite is required')]

    // private Collection $specialite;

    // #[ORM\Column(type: 'boolean')]
    // private $isVerified = false;

    // public function __construct()
    // {
    //     $this->specialite = new ArrayCollection();
    // }
    private $plainPassword;

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
        return $this->email_user;
    }

    public function setEmailUser(string $email_user): self
    {
        $this->email_user = $email_user;

        return $this;
    }

    public function getNomUser(): ?string
    {
        return $this->nom_user;
    }

    public function setNomUser(string $nom_user): self
    {
        $this->nom_user = $nom_user;

        return $this;
    }

    public function getPasswordUser(): ?string
    {
        return $this->password_user;
    }
    public function getPassword(): string
    {
        return $this->password_user;
    }
    public function setPassword(string $password): void
{
    $this->password_user = password_hash($password, PASSWORD_DEFAULT);
}

    public function setPasswordUser(string $password_user): self
    {
        $this->password_user = $password_user;

        return $this;
    }

    public function getRoleUser(): array
    {
        // return $this->role_user;
        // $role_user = $this->role_user;
          // guarantee every user at least has ROLE_USER
        $role_user[] = 'ROLE_CLIENT';

      
        $role_user[] = 'ROLE_PRO';
        $role_user[] = 'ROLE_ADMIN';

        return array_unique($role_user);
    }

    public function setRoleUser(array $role_user): self
    {
        $this->role_user = $role_user;

        return $this;
    }

    public function getAdresseUser(): ?string
    {
        return $this->adresse_user;
    }

    public function setAdresseUser(string $adresse_user): self
    {
        $this->adresse_user = $adresse_user;

        return $this;
    }

    // /**
    //  * @return Collection<int, specialite>
    //  */
    // public function getSpecialite(): Collection
    // {
    //     return $this->specialite;
    // }

    // public function addSpecialite(specialite $specialite): self
    // {
    //     if (!$this->specialite->contains($specialite)) {
    //         $this->specialite->add($specialite);
    //         $specialite->setUser($this);
    //     }

    //     return $this;
    // }

    // public function removeSpecialite(specialite $specialite): self
    // {
    //     if ($this->specialite->removeElement($specialite)) {
    //         // set the owning side to null (unless already changed)
    //         if ($specialite->getUser() === $this) {
    //             $specialite->setUser(null);
    //         }
    //     }

    //     return $this;
    // }
    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    public function getRoles(): array
    {
        return [];
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
    
    // public function __toString()
    // {
    //     return $this->speciality;
    // }


}
