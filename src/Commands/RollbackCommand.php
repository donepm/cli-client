<?php

namespace DonePM\ConsoleClient\Commands;

use Humbug\SelfUpdate\Updater;
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
        $updater = new Updater(null, false);
        try {
            $result = $updater->rollback();
            if ( ! $result) {
                $output->writeln('<error>Rollback failed!</error>');

                // report failure!
                return 1;
            }

            return 0;
        } catch (\Exception $e) {

            $output->writeln('<error>' . $e->getMessage() . '</error>');

            // Report an error!
            return 1;
        }
    }
}