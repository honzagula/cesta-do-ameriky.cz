<?php

namespace App\AdminModule\Presenters;

use App\Presenters\BasePresenter;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;
use WebLoader\Nette\LoaderFactory;

abstract class AdminPresenter extends BasePresenter
{
    /** @var LoaderFactory @inject */
    public $webLoader;

    protected function createComponentCss(): CssLoader
    {
        return $this->webLoader->createCssLoader('admin');
    }

    protected function createComponentJs(): JavaScriptLoader
    {
        return $this->webLoader->createJavaScriptLoader('admin');
    }
    
    public function startup(): void
    {
        if (!$this->user->isLoggedIn()) {
            $this->redirect('Login:default');
        }
        parent::startup();
    }
}
