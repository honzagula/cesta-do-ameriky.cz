<?php

namespace App\Form;

use App\Model\Article\Article;
use App\Model\Photo\PhotoUploader;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

final class PhotoUploadFormFactory
{
    /** @var IFormFactory */
    private $formFactory;

    /** @var PhotoUploader */
    private $photoUploader;

    public function __construct(IFormFactory $formFactory, PhotoUploader $photoUploader)
    {
        $this->formFactory = $formFactory;
        $this->photoUploader = $photoUploader;
    }

    public function create(Article $article): Form
    {
        $form = $this->formFactory->create(true);

        $form->addMultiUpload('photos', 'Fotky:')
            ->setRequired()
            ->addRule(Form::IMAGE)
        ->setAttribute('class', 'btn btn-default');

        $form->addSubmit('Upload', 'Nahrát');

        $form->onSuccess[] = function (Form $form, ArrayHash $values) use ($article) {
            foreach ($values->photos as $photo) {
                $this->photoUploader->uploadPhoto($article, $photo);
            }
        };

        return $form;
    }
}