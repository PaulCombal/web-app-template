<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LsUsrCommand extends Command
{
    protected static $defaultName = 'app:ls-usr';
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('List users having their email beginning with..')
            ->addArgument('email', InputArgument::OPTIONAL, 'Email start');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');

        $users = $this->em->createQueryBuilder()
            ->select('u')
            ->from('App:User', 'u')
            ->where('u.email LIKE :mail')
            ->setParameter('mail', "%$email%")
            ->getQuery()
            ->getResult();

        /** @var User $user */
        foreach ($users as $user) {
            $id = $user->getId();
            $mail = $user->getEmail();
            $io->text("$id.\t $mail");
        }

        return Command::SUCCESS;
    }
}
