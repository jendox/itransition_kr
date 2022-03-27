<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private Category $category;

    #[ORM\Column(type: 'text')]
    private string $body;

    #[ORM\Column(type: 'smallint')]
    private int $authorRating;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private User $author;

    #[ORM\OneToMany(mappedBy: 'review', targetEntity: Image::class, cascade: ['persist', 'remove'])]
    private Collection $images;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'reviews', cascade: ['persist'])]
    private Collection $tags;

    #[ORM\OneToMany(mappedBy: 'review', targetEntity: UserRating::class, cascade: ['persist', 'remove'])]
    private Collection $usersRating;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
        $this->images = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->usersRating = new ArrayCollection();
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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getAuthorRating(): ?int
    {
        return $this->authorRating;
    }

    public function setAuthorRating(int $authorRating): self
    {
        $this->authorRating = $authorRating;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setReview($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getReview() === $this) {
                $image->setReview(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection<int, UserRating>
     */
    public function getUsersRating(): Collection
    {
        return $this->usersRating;
    }

    public function getAvgUsersRating(): float
    {
        $usersRating = $this->getUsersRating();
        $result = 0;
        if ($usersRating->count()) {
            foreach ($usersRating as $rating) {
                $result += $rating->getValue();
            }
            $result = round($result/$usersRating->count(), 1);
        }
        return $result;
    }

    public function addUsersRating(UserRating $usersRating): self
    {
        if (!$this->usersRating->contains($usersRating)) {
            $this->usersRating[] = $usersRating;
            $usersRating->setReview($this);
        }

        return $this;
    }

    public function removeUsersRating(UserRating $usersRating): self
    {
        if ($this->usersRating->removeElement($usersRating)) {
            // set the owning side to null (unless already changed)
            if ($usersRating->getReview() === $this) {
                $usersRating->setReview(null);
            }
        }

        return $this;
    }
}
