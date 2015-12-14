<?php

namespace DonePM\ConsoleClient\Commands;

use DonePM\ConsoleClient\Services\PharUpdateService;
use Symfony\Component\Console\Command\Command;
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
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $updateService = new PharUpdateService(self::UPDATE_PHAR_URL, self::UPDATE_VERSION_URL);

        $result = $updateService->update();

        if ($result->failed()) {
            $output->writeln('<error>Update failed.</error>');
        }

        return 0;
    }
}