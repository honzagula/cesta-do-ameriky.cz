<?php

namespace App\Form;

use App\Model\Article\Article;
use App\Model\Article\ArticleDaoInterface;
use App\Model\IPersister;
use Nette\Application\UI\Form;

final class ArticleRelationsFormFactory
{
    const MAX_LIMIT = 999999;

    /** @var IFormFactory */
    private $formFactory;

    /** @var IPersister */
    private $persister;

    /** @var ArticleDaoInterface */
    private $articleDao;

    public function __construct(IFormFactory $formFactory, IPersister $persister, ArticleDaoInterface $articleDao)
    {
        $this->formFactory = $formFactory;
        $this->persister = $persister;
        $this->articleDao = $articleDao;
    }

    public function create(Article $article = null)
    {
        $form = $this->formFactory->create(true);
        $form->addSelect('previousArticle', 'Předchozí článek:', $this->getPreviousArticles());

        $form->addSubmit('submit', 'Uložit');

        $form->setDefaults([
            'previousArticle' => $article->getPreviousArticle() ? $article->getPreviousArticle()->getId() : null,
        ]);

        $form->onSuccess[] = function (Form $form) use ($article) {
            $values = $form->getValues();

            $this->handlePrevious($article, $values->previousArticle ? (int) $values->previousArticle : null);
        };

        return $form;
    }

    private function handlePrevious(Article $article, ?int $previousId): void
    {
        if ($previousId !== null) {
            // remove old relation if exists
            $oldPrevious = $article->getPreviousArticle();
            if ($oldPrevious) {
                $oldPrevious->setNextArticle(null);
            }

            // create new relation
            $previousArticle = $this->articleDao->findById($previousId);
            $article->setPreviousArticle($previousArticle);
            $previousArticle->setNextArticle($article);
        } else {
            if ($article->getPreviousArticle() !== null) {
                $article->getPreviousArticle()->setNextArticle(null);
                $article->setPreviousArticle(null);
            }
        }

        $this->persister->flush();
    }

    private function getPreviousArticles()
    {

        $articles = $this->articleDao->findByNewest(self::MAX_LIMIT, 0, true);
        $result = [];
        $result[null] = '-';
        foreach ($articles as $article) {
            $result[$article->getId()] = $article->getTitle() . ' | ' . $article->getCreatedAt()->format('d.m.Y');
        }

        return $result;
    }
}