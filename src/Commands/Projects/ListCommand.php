<?php

namespace DonePM\ConsoleClient\Commands\Projects;

use DonePM\ConsoleClient\Commands\Command;
use DonePM\ConsoleClient\Http\Client;
use DonePM\ConsoleClient\Http\Commands\ProjectListCommand;
use DonePM\ConsoleClient\Repositories\Reader\JsonReader;
use Symfony\Component\Console\Helper\Table;
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

        $client = new Client();

        $configFile = $this->getConfigFile();
        if (file_exists($configFile)) {
            $config = (new JsonReader())->read($configFile);
        }

        $response = $client->send(new ProjectListCommand($config->get('token')));
        if ($response->getStatusCode() >= 300) {
            $this->error('No Projects found');
            return;
        }
        $projects = json_decode($response->getBody()->getContents(), true);

        $projectsList = $projects['data'];

        $table = new Table($this->output);
        $table->setHeaders(['Id', 'Slug', 'Project', 'Status']);

        foreach ($projectsList as $project) {
            $table->addRow([
                $project['id'],
                $project['attributes']['slug'],
                $project['attributes']['name'],
                $project['attributes']['status'],
            ]);
        }

        $table->render();
    }
}