<?php


namespace App\ModelVisitation;

use App\Model\Article\Article;
use App\Model\Article\ArticleDao;
use App\Model\IPersister;
use Nette\Http\Session;
use Nette\Http\SessionSection;

class VisitationHandler
{
    /** @var SessionSection */
    private $sessionSection;

    /** @var ArticleDao */
    private $articleDao;

    /** @var IPersister */
    private $persister;

    public function __construct(Session $session, ArticleDao $articleDao, IPersister $persister)
    {
        $this->sessionSection = $session->getSection('visitation');
        $this->sessionSection->setExpiration('12 hours');

        $this->articleDao = $articleDao;
        $this->persister = $persister;
    }

    public function handleVisit(Article $article): void
    {
        if (!$this->sessionSection->offsetExists($this->generateArticleKey($article))) {
            $article->addVisit();

            $this->sessionSection->offsetSet($this->generateArticleKey($article), true);
            $this->persister->flush();
        }
    }

    private function generateArticleKey(Article $article): string
    {
        return 'article-' . $article->getId();
    }
}
