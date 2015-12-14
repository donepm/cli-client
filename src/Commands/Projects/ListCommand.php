<?php

namespace DonePM\ConsoleClient\Commands\Projects;

use Symfony\Component\Console\Command\Command;
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
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Projects listed...');
    }
}