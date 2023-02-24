<?php

namespace App\Command;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:add-post',
    description: 'Add a new post',
)]
class AddPostCommand extends Command
{

    public function __construct(private EntityManagerInterface $manager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('title', InputArgument::REQUIRED, 'Title of a post')
            ->addArgument('content', InputArgument::REQUIRED, 'Content of a post')
            // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $title = $input->getArgument('title');

        if ($title) {
            $io->note(sprintf('Title: %s', $title));
        }

        $content = $input->getArgument('content');

        if ($title) {
            $io->note(sprintf('Content: %s', $content));
        }

        // if ($input->getOption('option1')) {
        //     // ...
        // }

        $post = new Post();
        $post->setTitle($title);
        $post->setContent($content);
        $this->manager->persist($post);
        $this->manager->flush();

        $io->success('Post is saved: '.$post->getId());

        return Command::SUCCESS;
    }
}
