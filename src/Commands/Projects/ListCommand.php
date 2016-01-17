<?php

namespace DonePM\ConsoleClient\Commands\Projects;

use DonePM\ConsoleClient\Commands\Command;
use DonePM\ConsoleClient\Http\Commands\Projects\IndexCommand;
use DonePM\ConsoleClient\Services\ProjectIdSlugMapper;
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

        $command = new IndexCommand();

        try {
            $response = $client->send($command);
        } catch (ClientException $e) {
            if ($e->getCode() === 401) {
                $this->callSilent('dpm:token');

                $client->setToken($this->getApplication()->resetConfig()->config()->get('token'));

                $response = $client->send($command);
            } else {
                $this->error($e->getMessage());

                return 1;
            }
        }

        if ($response->getStatusCode() >= 300) {
            $this->info('No Projects found');

            return 0;
        }

        $projects = json_decode($response->getBody()->getContents(), true);

        $projectsList = array_get($projects, 'data', []);

        if (empty($projectsList)) {
            $this->info('No Projects found');

            return 0;
        }

        $config = $this->getApplication()->config();

        $slugMapper = new ProjectIdSlugMapper($config);

        $table = new Table($this->output);
        $table->setHeaders(['Id', 'Slug', 'Project', 'Status']);

        foreach ($projectsList as $project) {
            $table->addRow([
                $project['id'],
                $project['attributes']['slug'],
                $project['attributes']['name'],
                $project['attributes']['status'],
            ]);

            $slugMapper->addMapping($project['id'], $project['attributes']['slug']);
        }

        $table->render();

        $this->getApplication()->writeConfig($slugMapper->getConfig());

        return 0;
    }
}