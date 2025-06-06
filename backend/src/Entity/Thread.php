<?php

namespace App\Entity;

use App\Repository\ThreadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\State\ThreadStateProcessor;

#[ORM\Entity(repositoryClass: ThreadRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['thread:read']],
    denormalizationContext: ['groups' => ['thread:write']],
    security: "is_granted('IS_AUTHENTICATED_FULLY')",
    processor: ThreadStateProcessor::class,
)]
class Thread
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['thread:read', 'thread:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['thread:read', 'thread:write', 'post:read'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['thread:read', 'thread:write', 'post:read'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['thread:read', 'thread:write'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'threads')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['thread:read', 'thread:write', 'post:read'])]
    private ?User $author = null;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'thread')]
    #[Groups(['thread:write'])]
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setThread($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getThread() === $this) {
                $post->setThread(null);
            }
        }

        return $this;
    }

    #[Groups(['thread:read', 'post:read'])]
    public function getFormattedCreatedAt(): ?string
    {
        return $this->createdAt?->format('d/m/y H:i');
    }
}
