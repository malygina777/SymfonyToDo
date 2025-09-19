<?php

namespace App\Command;

use App\Service\OverdueTaskService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:tasks:process-overdue', description: 'Move overdue tasks to URGENT and send emails')]
final class ProcessOverdueTasksCommand extends Command
{
    public function __construct(private OverdueTaskService $service) { parent::__construct(); }

    protected function execute(InputInterface $in, OutputInterface $out): int
    {
        $count = $this->service->process();
        $out->writeln("Processed: $count task(s).");
        return Command::SUCCESS;
    }
}