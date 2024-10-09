<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppReminderCommand extends Command
{
    protected static $defaultName = 'app:reminder';

    protected function configure()
    {
        $this
            ->setDescription('Sent to users reminder of their tasks')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
    
        $output->writeln([
            "Reminder",
            "=========",
            ""
        ]);
        $io->success('All reminders have been sent.');
    }
}
