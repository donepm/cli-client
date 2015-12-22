<?php

namespace DonePM\ConsoleClient\Commands\Projects;

use DonePM\ConsoleClient\Commands\Command;
use DonePM\ConsoleClient\Http\Commands\Projects\StoreCommand;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class CreateCommand
 *
 * Create a project
 *
 * @package DonePM\ConsoleClient\Commands\Projects
 */
class CreateCommand extends Command
{
    /**
     * configures the command
     */
    protected function configure()
    {
        $this
            ->setName('project:create')
            ->setDescription('Creates a new project')
            ->addArgument('name', InputArgument::REQUIRED, 'Project name to create')
            ->addOption('slug', 's', InputOption::VALUE_REQUIRED, 'Slug for the project')
            ->addOption('archived', 'a', InputOption::VALUE_NONE, 'Create an archived project')
            ->addOption('public', 'p', InputOption::VALUE_NONE, 'Create a public project')
        ;
    }

    /**
     * handles project creation
     */
    protected function handle()
    {
        $client = $this->getClient();

        $command = new StoreCommand();
        $command->setName($this->argument('name'))
            ->setSlug($this->option('slug'))
            ->setArchived($this->option('archived'))
            ->setPublic($this->option('public'));

        try {
            $response = $client->send($command);
        } catch (ClientException $e) {
            if ($e->getCode() === 401) {
                $this->callSilent('dpm:token');

                $this->getApplication()->resetConfig();

                $response = $client->send($command);
            } else {
                $this->error($e->getMessage());
                return;
            }
        }

        if ($response->getStatusCode() === 201) {
            $this->info('Project created');

            return;
        }

        $this->error('Something went wrong');
    }

}