<?php

namespace DonePM\ConsoleClient\Commands;

use DonePM\ConsoleClient\Services\PharUpdateService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RollbackCommand
 *
 * @package DonePM\ConsoleClient\Commands
 */
class RollbackCommand extends Command
{
    /**
     * configures the command
     */
    protected function configure()
    {
        $this
            ->setName('rollback')
            ->setDescription('Rollback to last version');
    }

    /**
     * executes the command
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $updateService = new PharUpdateService();

        $result = $updateService->rollback();

        if ($result->failed()) {
            $output->writeln('<error>Rollback failed.</error>');
        }

        return 0;
    }
}