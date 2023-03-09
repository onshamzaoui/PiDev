<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datedebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datefin = null;

    

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?Category $category = null;
   
    
    /**
     * @Assert\Image(
     *     maxSize = "1024k",
     *     mimeTypes = {"image/jpeg", "image/png", "image/gif"},
     *     mimeTypesMessage = "Please upload a valid image (JPEG, PNG, or GIF)"
     * )
     */
    private $imageFile;

    #[ORM\Column(length: 255)]
private string $image;
#[ORM\Column(type: Types::INTEGER)]
private int $likes = 0;

#[ORM\Column(type: Types::INTEGER)]
private int $dislikes = 0;

public function getLikes(): int
{
    return $this->likes;
}

public function setLikes(int $likes): void
{
    $this->likes = $likes;
}

public function getDislikes(): int
{
    return $this->dislikes;
}

public function setDislikes(int $dislikes): void
{
    $this->dislikes = $dislikes;
}
public function incrementLikes()
{
    $this->likes++;
}

public function incrementDislikes()
{
    $this->dislikes++;
}
    public function getImageFile(): ?UploadedFile
    {
        return $this->imageFile;
    }

    public function setImageFile(?UploadedFile $imageFile): void
    {
        $this->imageFile = $imageFile;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
    public function getImageUrl(): ?string
{
    if (!$this->image) {
        return null;
    }

    return '/uploads/events/' . $this->image;
}

   /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function uploadImage(): void
    {
        if (null === $this->getImageFile()) {
            return;
        }

        $filename = pathinfo($this->getImageFile()->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $filename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $this->getImageFile()->guessExtension();

        try {
            $this->getImageFile()->move(
                'uploads/events',
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        $this->setImage($newFilename);
        $this->setImageFile(null);
    }


}