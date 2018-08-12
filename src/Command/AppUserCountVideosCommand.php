<?php

namespace App\Command;

use App\Manager\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppUserCountVideosCommand extends Command
{
    private $userManager;
    protected static $defaultName = 'app:user-count-videos';

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
        parent::__construct();
    }
    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('email', InputArgument::REQUIRED, 'Email description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('email');
        $user = $this->userManager->getUserByEmail($arg1);

        $io->note(sprintf('You passed an argument: %s', $arg1));
        if ($user != null){
            $count = $this->userManager->getNumberOfVideos($arg1);
            $io->success('tu a '.$count);
        }else{
            $io->error('No user with that email...');
        }



    }
}

// Commande pour  compté le nombre de videos d'un utilisateur