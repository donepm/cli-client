<?php

namespace DonePM\ConsoleClient\Commands;

use Humbug\SelfUpdate\Updater;
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
            ->setDescription('Rollback to previous version when possible');
    }

    /**
     * executes the command
     *
     * @return int
     */
    protected function handle()
    {
        $updater = new Updater(null, false);
        try {
            $result = $updater->rollback();
            if ( ! $result) {
                $this->error('Rollback failed!');

                // report failure!
                return 1;
            }

            return 0;
        } catch (\Exception $e) {

            $this->error($e->getMessage());

            // Report an error!
            return 1;
        }
    }
}