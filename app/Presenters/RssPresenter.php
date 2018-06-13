<?php

namespace App\Presenters;

use App\Model\Article\Article;
use App\Model\Article\ArticleDaoInterface;

final class RssPresenter extends FrontPresenter
{
    const ARTICLE_LIMIT = 10;

    /** @var ArticleDaoInterface @inject */
    public $articleDao;

    /** @var Article[] */
    private $articles;


    public function actionDefault(): void
    {
        $this->articles = $this->articleDao->findByNewest(self::ARTICLE_LIMIT);
    }

    public function renderDefault(): void
    {
        $this->getTemplate()->setParameters([
            'articles' => $this->articles,
        ]);
    }
}