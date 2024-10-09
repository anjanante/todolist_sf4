<?php

namespace App\Command;

use App\Service\Reminder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppReminderCommand extends Command
{
    protected static $defaultName = 'app:reminder';
    private $reminder;

    public function __construct(?string $name = null, Reminder $reminder)
    {
        parent::__construct($name);
        $this->reminder = $reminder;
    }

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
        $reminds = $this->reminder->remind();        
        $io->success($reminds.' reminders have been sent.');
    }
}
