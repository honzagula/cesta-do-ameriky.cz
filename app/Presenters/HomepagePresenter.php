<?php

namespace App\Presenters;

use App\Model\Article\Article;
use App\Model\Article\ArticleDaoInterface;

final class HomepagePresenter extends FrontPresenter
{
    /** @var ArticleDaoInterface @inject */
    public $articleDao;

    /** @var Article[] */
    private $articles;

    public function actionDefault(): void
    {
        $this->articles = $this->articleDao->findByNewest();
    }

    public function renderDefault(): void
    {
        $this->getTemplate()->setParameters([
            'articles' => $this->articles,
        ]);
    }
}
