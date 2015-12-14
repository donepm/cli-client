<?php

namespace DonePM\ConsoleClient\Commands\Projects;

use DonePM\ConsoleClient\Commands\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListCommand
 *
 * @package DonePM\ConsoleClient\Commands\Projects
 */
class ListCommand extends Command
{
    /**
     * configures the command
     */
    protected function configure()
    {
        $this
            ->setName('project:list')
            ->setDescription('List all projects');
    }

    /**
     * executes the command
     */
    protected function handle()
    {
        $this->info('Projects listed...');
    }
}