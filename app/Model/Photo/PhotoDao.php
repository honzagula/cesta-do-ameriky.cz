<?php

namespace App\Model\Photo;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;

class PhotoDao
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function findById(int $id): Photo
    {
        $photo = $this->em->getRepository(Photo::class)->find($id);

        if ($photo === null) {
            throw new EntityNotFoundException(sprintf('Photo with id `%d` not found.', $id));
        }

        return $photo;
    }

}