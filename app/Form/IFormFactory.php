<?php

namespace App\Form;

use Nette\Application\UI\Form;

interface IFormFactory
{
    public function create(bool $ajax = false): Form;
}
