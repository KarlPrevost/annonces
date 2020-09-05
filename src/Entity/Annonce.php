<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 */
class Annonce
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="annonce")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="annonce")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="annonce",  orphanRemoval=true, cascade={"persist"})
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="favorite")
     */
    private $favorite;

    public function __construct()
    {
        $this->image = new ArrayCollection();
        $this->favorite = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(Image $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
            $image->setAnnonce($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->image->contains($image)) {
            $this->image->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAnnonce() === $this) {
                $image->setAnnonce(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFavorite(): Collection
    {
        return $this->favorite;
    }

    public function addFavorite(User $favorite): self
    {
        if (!$this->favorite->contains($favorite)) {
            $this->favorite[] = $favorite;
        }

        return $this;
    }

    public function removeFavorite(User $favorite): self
    {
        if ($this->favorite->contains($favorite)) {
            $this->favorite->removeElement($favorite);
        }

        return $this;
    }
}
