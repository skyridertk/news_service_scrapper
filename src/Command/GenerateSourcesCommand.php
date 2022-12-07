<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Controller\MessageController;
use Symfony\Component\Messenger\MessageBusInterface;

class GenerateSourcesCommand extends Command
{
    protected static $defaultName = 'generateSources';
    protected static $defaultDescription = 'This command will generate sources';

    private MessageController $messageController;
    private MessageBusInterface $bus;

    public function __construct(
        MessageController $messageController,
        MessageBusInterface $bus
    ) 
    {
        parent::__construct();
        $this->messageController = $messageController;
        $this->bus = $bus;
    }


    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to download news aricles')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Generating sources',
            '============',
            '',
        ]);

        $expenses = $this->messageController->createMessages($this->bus);

        $output->writeln([
            'done'
        ]);

        return Command::SUCCESS;
    }
}
