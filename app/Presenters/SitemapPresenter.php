<?php

namespace App\Presenters;

use App\Model\Article\Article;
use App\Model\Article\ArticleDaoInterface;
use Nette\Application\UI\Presenter;

final class SitemapPresenter extends Presenter
{
    const ARTICLE_LIMIT = 99999999999;

    /** @var ArticleDaoInterface @inject */
    public $articleDao;

    /** @var Article[] */
    private $articles;

    /** @var \DateTimeInterface */
    private $lastUpdatedAt;

    public function actionDefault(): void
    {
        $this->articles = $this->articleDao->findByNewest(self::ARTICLE_LIMIT);
        $this->lastUpdatedAt = $this->articleDao->getLastUpdate();
    }

    public function renderDefault(): void
    {
        $this->getTemplate()->setParameters([
            'articles' => $this->articles,
            'lastUpdateAt' => $this->lastUpdatedAt,
        ]);
    }
}