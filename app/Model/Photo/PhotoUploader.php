<?php

namespace App\Model\Photo;

use App\Model\Article\Article;
use Nette\Http\FileUpload;

class PhotoUploader
{
    /** @var PhotoFactory */
    private $photoFactory;

    /** @var PhotoStorage */
    private $photoStorage;

    public function __construct(PhotoFactory $photoFactory, PhotoStorage $photoStorage)
    {
        $this->photoFactory = $photoFactory;
        $this->photoStorage = $photoStorage;
    }

    public function uploadPhoto(Article $article, FileUpload $fileUpload): Photo
    {
        $photo = $this->photoFactory->create($article, $fileUpload->getName(), $fileUpload->getSize());

        $this->photoStorage->storagePhoto($fileUpload, $photo);

        return $photo;
    }

}