<?php

namespace App\Form;

use App\Model\Article\Article;
use App\Model\IPersister;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

final class ArticleMetaFormFactory
{
    /** @var IFormFactory */
    private $formFactory;

    /** @var IPersister */
    private $persister;

    public function __construct(IFormFactory $formFactory, IPersister $persister)
    {
        $this->formFactory = $formFactory;
        $this->persister = $persister;
    }

    public function create(Article $article): Form
    {
        $form = $this->formFactory->create();

        $form->addText('socialTitle', 'Nadpis:');
        $form->addTextArea('socialContent', 'Obsah:');

        $form->addSubmit('submit', 'Uložit');

        $form->onSuccess[] = function (Form $form, ArrayHash $values) use ($article) {
            $article->setSocialTitle($values->socialTitle);
            $article->setSocialContent($values->socialContent);

            $this->persister->flush();
        };

        $form->setDefaults([
            'socialTitle' => $article->getSocialTitle(),
            'socialContent' => $article->getSocialContent(),
        ]);

        return $form;
    }
}