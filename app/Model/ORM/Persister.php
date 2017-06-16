<?php

namespace App\Model;

use Doctrine\ORM\EntityManager;

class Persister implements IPersister
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function flush(): void
    {
        $this->em->flush();
    }

    public function persist($entity): void
    {
        $this->em->persist($entity);
    }

    public function save($entity): void
    {
        $this->persist($entity);
        $this->flush();
    }

    public function remove($entity): void
    {
        $this->em->remove($entity);
    }

    public function delete($entity): void
    {
        $this->remove($entity);
        $this->flush();
    }
}