<?php

namespace DonePM\ConsoleClient\Commands;

use Humbug\SelfUpdate\Updater;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SelfUpdateCommand
 *
 * @package DonePM\ConsoleClient\Commands
 */
class SelfUpdateCommand extends Command
{
    const UPDATE_PHAR_URL = 'https://donepm.github.io/cli-client/dpm.phar';
    const UPDATE_VERSION_URL = 'https://donepm.github.io/cli-client/dpm.phar.version';

    /**
     * configures the command
     */
    protected function configure()
    {
        $this
            ->setName('self-update')
            ->setDescription('Updates itself');
    }

    /**
     * executes the command
     *
     * @return int
     */
    protected function handle()
    {
        $updater = new Updater(null, false);
        /** @var \Humbug\SelfUpdate\Strategy\ShaStrategy $strategy */
        $strategy = $updater->getStrategy();
        $strategy->setPharUrl(self::UPDATE_PHAR_URL);
        $strategy->setVersionUrl(self::UPDATE_VERSION_URL);
        try {
            $result = $updater->update();
            if ( ! $result) {
                // No update needed!
                return 0;
            }
            $new = $updater->getNewVersion();
            $old = $updater->getOldVersion();
            $this->info(sprintf('Updated from %s to %s', $old, $new));

            return 0;
        } catch (\Exception $e) {
            // Report an error!

            $this->error($e->getMessage());

            return 1;
        }
    }
}