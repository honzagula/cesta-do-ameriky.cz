<?php
namespace App\Model\User;

use App\Model\Article\Article;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class User extends \Instante\Doctrine\Users\User
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $name;

    /**
     * @var Article[]|ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="App\Model\Article\Article", mappedBy="user")
     */
    private $articles;

    public function __construct(string $email, string $name, string $salt, string $password)
    {
        $this->email = $email;
        $this->name = $name;
        $this->articles = new ArrayCollection();
        
        parent::__construct($salt, $password);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
