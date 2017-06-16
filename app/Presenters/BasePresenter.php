<?php

namespace App\Presenters;

use App\Model\Photo\Photo;
use App\Model\Photo\PhotoStorage;
use Instante\RequireJS\Components\JsLoader;
use Instante\RequireJS\Components\JsLoaderFactory;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Presenter;
use Nette\Http\Request;

abstract class BasePresenter extends Presenter
{
    const DEFAULT_IMAGE = 'nyc-full-min.jpg';

    /** @var EntityManager @inject */
    public $em;

    /** @var JsLoaderFactory @inject */
    public $jsLoaderFactory;

    /** @var PhotoStorage @inject */
    public $photoStorage;

    /** @var Request @inject */
    public $httpRequest;

    protected function beforeRender(): void
    {
        parent::beforeRender();
        $this->addHelpers();
        $this->addBasicVariables();
    }

    protected function addHelpers(): void
    {
        $template = $this->getTemplate();

        $template->addFilter('img', function (Photo $photo = null) {
            return $this->getBasePath() . '/' . $this->photoStorage->getPhotoFolder() . '/' . $photo->getId() . '.jpg';
        });
        $template->addFilter('imgThumb', function (Photo $photo = null) {
            return $this->getBasePath() . '/' . $this->photoStorage->getPhotoFolder() . '/' . $photo->getId() . '_thumb.jpg';
        });
        $template->addFilter('md', function ($text) {
            return $this->markdown->parse($text);
        });
    }

    protected function getBasePath(): string
    {
        return rtrim($this->httpRequest->getUrl()->getBasePath(), '/');
    }

    protected function getHostname(): string
    {
        return rtrim($this->httpRequest->getUrl()->getHostUrl(), '/');
    }

    public function isModuleCurrent(string $module): bool
    {
        if (!$lastSeparatorPosition = strrpos($this->name, ':')) { // not in module
            return false;
        }

        return ltrim($module, ':') === substr($this->name, 0, $lastSeparatorPosition);
    }

    public function flashInfo($message): \stdClass
    {
        return $this->flashMessage($message, 'info');
    }

    public function flashWarning($message): \stdClass
    {
        return $this->flashMessage($message, 'warning');
    }

    public function flashSuccess($message): \stdClass
    {
        return $this->flashMessage($message, 'success');
    }

    public function flashDanger($message): \stdClass
    {
        return $this->flashMessage($message, 'danger');
    }

    protected function createComponentJsLoader(): JsLoader
    {
        return $this->jsLoaderFactory->create();
    }

    private function addBasicVariables(): void
    {
        $template = $this->getTemplate();
        $template->hostname = $this->getHostname();
        $template->defaultImage = self::DEFAULT_IMAGE;
    }
}
