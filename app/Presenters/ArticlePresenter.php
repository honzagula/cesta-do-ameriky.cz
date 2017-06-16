<?php

namespace App\Presenters;

use App\Model\Article\Article;
use App\Model\Article\ArticleDao;
use Nette\Application\ForbiddenRequestException;

final class ArticlePresenter extends FrontPresenter
{
    /** @var ArticleDao @inject */
    public $articleDao;

    /** @var Article */
    private $article;

    public function actionDefault(int $id): void
    {
        $this->article = $this->articleDao->findById($id);
        if (!$this->article->isPublished()) {
            if (!$this->user->isLoggedIn()) {
                throw new ForbiddenRequestException('Article is not published');
            }
        }
    }

    public function renderDefault(): void
    {
        $this->getTemplate()->setParameters([
            'article' => $this->article,
            'articleLink' => $this->generateLink(),
        ]);
    }

    private function generateLink(): string
    {
        return $this->link("//Article:", [
            "id" => $this->article->getId(),
            "slug" => $this->article->getSlug(),
        ]);
    }
}
