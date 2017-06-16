<?php

namespace App\Command;

use App\Model\IPersister;
use App\Model\User\User;
use Nette\Utils\Random;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('app:create-admin');
        $this->setDescription('Creates admin user');
        $this->addArgument('username', InputArgument::REQUIRED);
        $this->addArgument('email', InputArgument::REQUIRED);
        $this->addArgument('password', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        /** @var IPersister $persister */
        $persister = $this->getHelper('container')->getByType(IPersister::class);
        
        $admin = new User(
            $input->getArgument('email'),
            $input->getArgument('username'),
            Random::generate(6),
            $input->getArgument('password')
            );
        
        $persister->save($admin);
    }


}
