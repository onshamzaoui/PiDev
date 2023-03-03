<?php

namespace App\Entity;

use App\Repository\SpecialityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: SpecialityRepository::class)]
class Speciality
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Regex("/^[A-Z][a-z]/")]
    #[Assert\Length(max:255)]
    #[Assert\Type(type:'string')]
    private ?string $speciality_name = null;

    #[ORM\OneToMany(mappedBy: 'speciality', targetEntity: User::class)]
    private Collection $users;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max : 255, maxMessage : "Description cannot be longer than {{ limit }} characters")]
    private ?string $description = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpecialityName(): ?string
    {
        return $this->speciality_name;
    }

    public function setSpecialityName(string $speciality_name): self
    {
        $this->speciality_name = $speciality_name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setSpeciality($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSpeciality() === $this) {
                $user->setSpeciality(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->speciality_name;
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
