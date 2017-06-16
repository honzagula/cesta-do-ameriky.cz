<?php

namespace App\Model\Photo;

use App\Model\IPersister;

class PhotoRemover
{
    /** @var IPersister */
    private $persister;

    /** @var PhotoStorage */
    private $photoStorage;

    public function __construct(IPersister $persister, PhotoStorage $photoStorage)
    {
        $this->persister = $persister;
        $this->photoStorage = $photoStorage;
    }

    public function removePhoto(Photo $photo): void
    {
        @unlink($this->photoStorage->getWwwFolder() . '/' . $this->photoStorage->getPhotoFolder() . '/' . $photo->getId() . '.' . PhotoStorage::getExtension($photo->getName()));
        @unlink($this->photoStorage->getWwwFolder() . '/' . $this->photoStorage->getPhotoFolder() . '/' . $photo->getId() . '_thumb.' . PhotoStorage::getExtension($photo->getName()));
        $this->persister->delete($photo);
    }
}