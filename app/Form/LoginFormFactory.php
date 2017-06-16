<?php

namespace App\Form;

use App\Model\User\UserDao;
use Doctrine\ORM\EntityNotFoundException;
use Nette\Application\UI\Form;
use Nette\Security\User;

final class LoginFormFactory
{
    /** @var IFormFactory */
    private $formFactory;

    /** @var UserDao */
    private $userDao;

    /**  @var User */
    private $user;

    public function __construct(
        IFormFactory $formFactory,
        UserDao $userDao,
        User $user
    )
    {
        $this->formFactory = $formFactory;
        $this->userDao = $userDao;
        $this->user = $user;
    }

    public function create(): Form
    {
        $form = $this->formFactory->create();
        
        $form->addText('email', 'E-mail:')
            ->setRequired();
        $form->addPassword('password', 'Heslo:')
            ->setRequired();
        $form->addSubmit('submit', 'Přihlásit');

        $form->onSuccess[] = function (Form $form) {
            $values = $form->getValues();

            try {
                $user = $this->userDao->getUserByEmail($values->email);
            } catch (EntityNotFoundException $e) {
                $form['email']->addError('Tento uživatel neexistuje');
                return;
            }

            if ($user->checkPassword($values->password)) {
                $this->user->login($user);
            } else {
                $form['password']->addError('Špatné heslo');
                return;
            }

        };
        
        return $form;
    }
}
