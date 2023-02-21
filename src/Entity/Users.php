<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email', 'nickname'], message: 'Cet utilisateur existe déjà')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(options: ['default' => 'ROLE_USER'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 70)]
    private ?string $lastname = null;

    #[ORM\Column(length: 100)]
    private ?string $firstname = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $nickname = null;

    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $is_admin = false;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $is_verified = false;

    #[ORM\Column(options: ['default' => true])]
    private ?bool $is_enabled = true;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $resetToken = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Articles::class)]
    private Collection $articles;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Images::class)]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CommentsImage::class)]
    private Collection $commentsImages;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CommentsArticle::class)]
    private Collection $commentsArticles;

    public $user;

    

    public function __construct($user)
    {
        $this->articles = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->commentsImages = new ArrayCollection();
        $this->commentsArticles = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
        //$this->roles = new ArrayCollection();
        $this->user = $user;
    }

    public function __toString(): string
    {
        if(is_null($this->user)){
            return 'NULL';
        }
        return $this->user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

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

    public function isIsAdmin(): ?bool
    {
        return $this->is_admin;
    }

    public function setIsAdmin(bool $is_admin): self
    {
        $this->is_admin = $is_admin;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(bool $is_verified): self
    {
        $this->is_verified = $is_verified;

        return $this;
    }

    public function isIsEnabled(): ?bool
    {
        return $this->is_enabled;
    }

    public function setIsEnabled(bool $is_enabled): self
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    /**
     * @return Collection<int, Articles>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setUser($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getUser() === $this) {
                $image->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommentsImage>
     */
    public function getCommentsImages(): Collection
    {
        return $this->commentsImages;
    }

    public function addCommentsImage(CommentsImage $commentsImage): self
    {
        if (!$this->commentsImages->contains($commentsImage)) {
            $this->commentsImages->add($commentsImage);
            $commentsImage->setUser($this);
        }

        return $this;
    }

    public function removeCommentsImage(CommentsImage $commentsImage): self
    {
        if ($this->commentsImages->removeElement($commentsImage)) {
            // set the owning side to null (unless already changed)
            if ($commentsImage->getUser() === $this) {
                $commentsImage->setUser(null);
            }
        }

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
            $commentsArticle->setUser($this);
        }

        return $this;
    }

    public function removeCommentsArticle(CommentsArticle $commentsArticle): self
    {
        if ($this->commentsArticles->removeElement($commentsArticle)) {
            // set the owning side to null (unless already changed)
            if ($commentsArticle->getUser() === $this) {
                $commentsArticle->setUser(null);
            }
        }

        return $this;
    }

}
