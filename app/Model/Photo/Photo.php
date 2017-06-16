<?php

namespace App\Model\Photo;

use App\Model\Article\Article;
use App\Model\ORM\TEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Photo
{
    use TEntity;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @var Article
     * 
     * @ORM\ManyToOne(targetEntity="App\Model\Article\Article", inversedBy="photos")
     */
    private $article;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $published = true;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    public function __construct(Article $article, $name, $size)
    {
        $this->size = $size;
        $this->article = $article;
        $this->name = $name;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getArticle(): Article
    {
        return $this->article;
    }

    public function setArticle(Article $article): void
    {
        $this->article = $article;
    }

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): void
    {
        $this->published = $published;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
