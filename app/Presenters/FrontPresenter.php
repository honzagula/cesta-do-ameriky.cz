<?php

namespace App\Presenters;

use App\Model\Social\SocialConfig;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;
use WebLoader\Nette\LoaderFactory;

abstract class FrontPresenter extends BasePresenter
{
    /** @var LoaderFactory @inject */
    public $webLoader;

    /** @var SocialConfig @inject */
    public $socialConfig;

    public function beforeRender(): void
    {
        parent::beforeRender();
        $template = $this->getTemplate();
        $template->socialConfig = $this->socialConfig;
    }

    protected function createComponentCss(): CssLoader
    {
        return $this->webLoader->createCssLoader('front');
    }

    protected function createComponentJs(): JavaScriptLoader
    {
        return $this->webLoader->createJavaScriptLoader('front');
    }


}
