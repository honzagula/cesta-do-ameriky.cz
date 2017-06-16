<?php

namespace App\Model\ORM;

use Doctrine\ORM\EntityManager;

abstract class BaseDao
{
    /** @var EntityManager */
    protected $em;
    
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
}
