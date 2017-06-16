<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Datagrid\ArticleDatagridFactory;
use App\Form\ArticleFormFactory;
use App\Form\ArticleMetaFormFactory;
use App\Form\ArticleRelationsFormFactory;
use App\Form\PhotoUploadFormFactory;
use App\Model\Article\Article;
use App\Model\Article\ArticleDao;
use App\Model\IPersister;
use App\Model\Photo\PhotoDao;
use App\Model\Photo\PhotoRemover;
use Doctrine\ORM\EntityNotFoundException;
use Nette\Application\UI\Form;
use Nette\Utils\Random;
use Ublaboo\DataGrid\DataGrid;
use WebLoader\Nette\CssLoader;

final class ArticlePresenter extends AdminPresenter
{
    const LIMIT = 30;
    
    /** @var ArticleDao @inject */
    public $articleDao;
    
    /** @var ArticleFormFactory @inject */
    public $articleFormFactory;

    /** @var PhotoUploadFormFactory @inject */
    public $photoUploadFormFactory;

    /** @var PhotoRemover @inject */
    public $photoRemover;

    /** @var PhotoDao @inject */
    public $photoDao;

    /** @var IPersister @inject */
    public $persister;

    /** @var ArticleMetaFormFactory @inject */
    public $articleMetaFormFactory;

    /** @var ArticleDatagridFactory @inject */
    public $articleDatagridFactory;

    /** @var ArticleRelationsFormFactory @inject */
    public $articleRelationsFormFactory;

    /** @var Article[] */
    private $articles;

    /** @var Article */
    private $article;

    public function actionDefault(int $offset = 0): void
    {
        $this->articles = $this->articleDao->findByNewest(self::LIMIT, $offset, false);
    }

    public function renderDefault(): void
    {
        $this->getTemplate()->setParameters([
            'articles' => $this->articles,
        ]);
    }
    
    public function actionEdit(int $id): void
    {
        $this->article = $this->articleDao->findById($id);
    }

    public function renderEdit(): void
    {
        $this->getTemplate()->setParameters([
            'article' => $this->article,
        ]);
    }

    public function renderAdd(): void
    {
        $this->getTemplate()->setParameters([
            'random' => Random::generate(6),
        ]);
    }
    
    protected function createComponentAddArticleForm(): Form
    {
        $form = $this->articleFormFactory->create();
        $form->onSuccess[] = function () {
            $this->flashSuccess('Článek přidán');
            $this->redirect('Article:');
        };
            
        return $form; 
    }

    protected function createComponentArticleEditForm(): Form
    {
        $form = $this->articleFormFactory->create($this->article);
        $form->onSuccess[] = function () {
            $this->flashSuccess('Článek upraven');
            $this->redrawControl('articleTab');
            $this->redrawControl('metaTab');
            $this->redrawControl('status');
            $this->redrawControl('initMDSnippet');
        };

        return $form;
    }

    protected function createComponentPhotoForm(): Form
    {
        $form = $this->photoUploadFormFactory->create($this->article);
        $form->onSuccess[] = function () {
            $this->flashSuccess('Fotky nahrány');
            $this->redrawControl('photosTab');
            $this->redrawControl('status');
        };

        return $form;
    }

    protected function createComponentMetaForm(): Form
    {
        $form = $this->articleMetaFormFactory->create($this->article);
        $form->onSuccess[] = function () {
            $this->flashSuccess('Uloženo');
            $this->redrawControl('metaTab');
            $this->redrawControl('status');
        };

        return $form;
    }

    public function handleRemovePhoto(int $photoId): Form
    {
        try {
            $photo = $this->photoDao->findById($photoId);
            $this->photoRemover->removePhoto($photo);
        } catch (EntityNotFoundException $e) {
            $this->flashDanger('Fotka nenalezena');
        }

        $this->redrawControl('status');
        $this->redrawControl('photosTab');
    }

    public function handleSetMainPhoto(int $photoId): void
    {
        try {
            $photo = $this->photoDao->findById($photoId);
            $this->article->setMainPhoto($photo);

            $this->persister->flush();
        } catch (EntityNotFoundException $e) {
            $this->flashDanger('Fotka nenalezena');
        }

        $this->redrawControl('status');
        $this->redrawControl('photosTab');
    }

    protected function createComponentDatagrid(): DataGrid
    {
        $datagrid = $this->articleDatagridFactory->create();

        return $datagrid;
    }

    protected function createComponentRelationsForm(): Form
    {
        $form = $this->articleRelationsFormFactory->create($this->article);

        $form->onSuccess[] = function () {
            $this->flashSuccess('Vazby uloženy');
            $this->redrawControl('status');
            $this->redrawControl('relationTab');
        };

        return $form;
    }
}
