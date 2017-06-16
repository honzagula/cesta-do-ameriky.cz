<?php

namespace App\Model\Photo;

use App\Model\Article\Article;
use App\Model\Persister;

class PhotoFactory
{
    /** @var Persister */
    private $persister;

    public function __construct(Persister $persister)
    {
        $this->persister = $persister;
    }

    public function create(Article $article, string $name, int $size): Photo
    {
        $photo = new Photo($article, $name, $size);

        $this->persister->save($photo);

        return $photo;
    }
}