<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakeAdminCommand extends Command
{
    protected static $defaultName = 'app:make-admin';
    private $em;

    protected function configure()
    {
        $this
            ->setDescription('Grant admin role to a user')
            ->addArgument('user_id', InputArgument::REQUIRED, 'User ID to make admin')
        ;
    }

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = intval($input->getArgument('user_id'));

        $user = $this->em->getRepository(User::class)->find($arg1);
        if (!$user) {
            $io->error('Could not find user');
            return Command::FAILURE;
        }

        /** @var $user User */
        $user->setRoles(['ROLE_ADMIN']);
        $this->em->persist($user);
        $this->em->flush();

        $io->success('Marked ' . $user->getEmail() . ' as admin');

        return Command::SUCCESS;
    }
}
