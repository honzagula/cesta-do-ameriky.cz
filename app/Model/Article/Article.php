<?php

namespace App\Model\Article;

use App\Model\ORM\TEntity;
use App\Model\Photo\Photo;
use App\Model\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{
    use TEntity;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $subtitle;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @var Photo[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Model\Photo\Photo", mappedBy="article")
     */
    private $photos;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Model\User\User", inversedBy="articles")
     */
    private $user;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="App\Model\Article\Article")
     */
    private $previousArticle;

    /**
     * @var Article
     *
     * @ORM\ManyToOne(targetEntity="App\Model\Article\Article")
     */
    private $nextArticle;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $visited = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $socialTitle = '';

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $socialContent = '';

    /**
     * @var Photo
     *
     * @ORM\ManyToOne(targetEntity="App\Model\Photo\Photo")
     */
    private $mainPhoto;

    public function __construct(User $user, string $title, string $slug)
    {
        $this->user = $user;
        $this->title = $title;
        $this->slug = $slug;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function setPhotos(ArrayCollection $photos): void
    {
        $this->photos = $photos;
    }

    public function addPhoto(Photo $photo): void
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
        }
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function isPublished(): bool 
    {
        return $this->published;
    }

    public function setPublished(bool $published): void
    {
        $this->published = $published;
    }

    public function getPreviousArticle(): ?Article
    {
        return $this->previousArticle;
    }

    public function setPreviousArticle(?Article $previousArticle): void
    {
        $this->previousArticle = $previousArticle;
        //$previousArticle->setNextArticle($this);
    }

    public function getNextArticle(): ?Article
    {
        return $this->nextArticle;
    }

    public function setNextArticle(?Article $nextArticle): void
    {
        $this->nextArticle = $nextArticle;
    }

    public function getVisited(): int
    {
        return $this->visited;
    }

    public function addVisit(int $visit = 1): void
    {
        $this->visited += $visit;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getMainPhoto(): ?Photo
    {
        return $this->mainPhoto;
    }

    public function setMainPhoto(Photo $mainPhoto): void
    {
        $this->mainPhoto = $mainPhoto;
    }

    public function getSocialTitle(): string
    {
        return !empty($this->socialTitle) ? $this->socialTitle : $this->getTitle();
    }

    public function getSocialContent(): string
    {
        return !empty($this->socialContent) ? $this->socialContent : substr($this->getContent(), 0, 300) . '...';
    }

    public function getSocialPhoto(): ?Photo
    {
        if ($this->getMainPhoto()) {
            return $this->getMainPhoto();
        } else {
            return null;
        }
    }

    public function setSocialTitle(string $socialTitle): void
    {
        $this->socialTitle = $socialTitle;
    }

    public function setSocialContent(string $socialContent): void
    {
        $this->socialContent = $socialContent;
    }

    public function getParsedContent(): string
    {
        $parsedown = new \Parsedown();
        return $parsedown->parse($this->getContent());
    }

    public function getClearedContent(): string
    {
        return strip_tags($this->getParsedContent());
    }
}
