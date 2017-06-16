<?php

namespace App\AdminModule\Presenters;

use App\Form\LoginFormFactory;
use App\Presenters\BasePresenter;
use Instante\RequireJS\Components\JsLoader;
use Nette\Application\UI\Form;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;
use WebLoader\Nette\LoaderFactory;

final class LoginPresenter extends BasePresenter
{
    /** @var LoginFormFactory @inject */
    public $loginFormFactory;

    /** @var LoaderFactory @inject */
    public $webLoader;

    /** @return CssLoader */
    protected function createComponentCss(): CssLoader
    {
        return $this->webLoader->createCssLoader('admin');
    }

    /** @return JavaScriptLoader */
    protected function createComponentJs(): JavaScriptLoader
    {
        return $this->webLoader->createJavaScriptLoader('admin');
    }
    
    public function createComponentLoginForm(): Form
    {
        $form = $this->loginFormFactory->create();

        $form->onSuccess[] = function () {
            $this->redirect(':Admin:Homepage:');
        };
        return $form;
    }
    
    public function actionLogout(): void
    {
        $this->user->logout(true);
        $this->redirect(':Admin:Homepage:');
    }
}
