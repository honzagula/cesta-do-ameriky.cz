<?php

namespace App\Form;

use App\Model\Article\Article;
use App\Model\Article\ArticleFactory;
use App\Model\IPersister;
use Nette\Application\UI\Form;
use Nette\Security\User;
use Nette\Utils\ArrayHash;

final class ArticleFormFactory
{
    /** @var IFormFactory */
    private $formFactory;

    /** @var ArticleFactory */
    private $articleFactory;

    /** @var User */
    private $user;

    /** @var IPersister */
    private $persister;

    public function __construct(IFormFactory $formFactory, ArticleFactory $articleFactory, User $user, IPersister $persister)
    {
        $this->formFactory = $formFactory;
        $this->articleFactory = $articleFactory;
        $this->user = $user;
        $this->persister = $persister;
    }

    public function create(Article $article = null): Form
    {
        $form = $this->formFactory->create(false);

        $form->addText('title', 'Nadpis:')
            ->setRequired();

        $form->addText('subtitle', 'Podnadpis:');

        $form->addText('slug', 'Slug:')
            ->setRequired();

        $form->addTextArea('content', 'Obsah:')
            ->setAttribute('rows', 8)
            ->setHtmlId('article-content')
            ->setAttribute('class', 'mde');

        $form->addCheckbox('published', 'Publikováno');

        $form->addSubmit('submit', 'Uložit')
            ->setAttribute('class', 'mde-submit');

        if ($article !== null) {
            $form->setDefaults([
                'title' => $article->getTitle(),
                'slug' => $article->getSlug(),
                'subtitle' => $article->getSubtitle(),
                'content' => $article->getContent(),
                'published' => $article->isPublished(),
            ]);
        }

        $form->onSuccess[] = function (Form $form, ArrayHash $values) use ($article) {
            if ($article === null) {
                $article = $this->articleFactory->createArticle($this->user->getIdentity(), $values->title, $values->slug, $values->content, $values->published);
                $article->setSubtitle($values->subtitle);

                $this->persister->flush();
            } else {
                $article->setContent($values->content);
                $article->setTitle($values->title);
                $article->setPublished($values->published);
                $article->setSubtitle($values->subtitle);
                $article->setSlug($values->slug);

                $this->persister->flush();
            }
        };

        return $form;
    }
}
