<?php

namespace App\Form;

use Instante\Bootstrap3Renderer\BootstrapRenderer;
use Nette\Application\UI\Form;

final class BootstrapFormFactory implements IFormFactory
{

    public function create(bool $ajax = false): Form
    {
        $form = new Form();
        $form->setRenderer(new BootstrapRenderer());

        if ($ajax) {
            $form->getElementPrototype()->setAttribute('class', 'ajax');
        }
        
        return $form;
    }
}
