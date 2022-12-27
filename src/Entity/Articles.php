<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\SlugTrait;
use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlesRepository::class)]
class Articles
{
    use CreatedAtTrait;
    use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 300)]
    private ?string $summary = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: CommentsArticle::class)]
    private Collection $commentsArticles;

    public function __construct()
    {
        $this->commentsArticles = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }

    public function __toString()
    {
        return $this->title;
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

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, CommentsArticle>
     */
    public function getCommentsArticles(): Collection
    {
        return $this->commentsArticles;
    }

    public function addCommentsArticle(CommentsArticle $commentsArticle): self
    {
        if (!$this->commentsArticles->contains($commentsArticle)) {
            $this->commentsArticles->add($commentsArticle);
            $commentsArticle->setArticle($this);
        }

        return $this;
    }

    public function removeCommentsArticle(CommentsArticle $commentsArticle): self
    {
        if ($this->commentsArticles->removeElement($commentsArticle)) {
            // set the owning side to null (unless already changed)
            if ($commentsArticle->getArticle() === $this) {
                $commentsArticle->setArticle(null);
            }
        }

        return $this;
    }
}
