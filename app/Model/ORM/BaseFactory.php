<?php

namespace App\Model\ORM;

use App\Model\IPersister;

abstract class BaseFactory
{
    /** @var IPersister */
    protected $persister;
    
    public function __construct(IPersister $persister)
    {
        $this->persister = $persister;
    }

}
