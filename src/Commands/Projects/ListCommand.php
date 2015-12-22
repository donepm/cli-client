<?php

namespace DonePM\ConsoleClient\Commands\Projects;

use DonePM\ConsoleClient\Commands\Command;
use DonePM\ConsoleClient\Http\Commands\ProjectListCommand;
use GuzzleHttp\Exception\ClientException;
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
        $client = $this->getClient();

        try {
            $response = $client->send(new ProjectListCommand());
        } catch (ClientException $e) {
            if ($e->getCode() === 401) {
                $this->callSilent('dpm:token');

                $this->getApplication()->resetConfig();

                $response = $client->send(new ProjectListCommand());
            } else {
                $this->error($e->getMessage());
                return;
            }
        }

        if ($response->getStatusCode() >= 300) {
            $this->info('No Projects found');

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