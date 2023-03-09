<?php

namespace App\Entity;

use Symfony\Component\Validator\Regex;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]

    #[Assert\Regex('/^[a-z]+$/i',message:"entrez un nom valide")]
    

    private ?string $namecategory = null;

    #[ORM\Column(nullable: true)]
      /**
     * @Assert\Range(
     *      min = 1,
     *      max = 100,
     *      notInRangeMessage = "The reduction rate must be between {{ min }} and {{ max }}.",
     *      invalidMessage = "Please enter a valid reduction rate."
     * )
     */
    private ?int $tauxreduction = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Event::class)]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamecategory(): ?string
    {
        return $this->namecategory;
    }

    public function setNamecategory(string $namecategory): self
    {
        $this->namecategory = $namecategory;

        return $this;
    }

    public function getTauxreduction(): ?int
    {
        return $this->tauxreduction;
    }

    public function setTauxreduction(?int $tauxreduction): self
    {
        $this->tauxreduction = $tauxreduction;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setCategory($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getCategory() === $this) {
                $event->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return  $this->namecategory;
    }
}
