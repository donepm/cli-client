<?php

namespace DonePM\ConsoleClient\Commands\Projects;

use DonePM\ConsoleClient\Commands\Command;
use DonePM\ConsoleClient\Http\Commands\Projects\ShowCommand;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InfoCommand
 *
 * @package DonePM\ConsoleClient\Commands\Projects
 */
class InfoCommand extends Command
{
    /**
     * configures the command
     */
    protected function configure()
    {
        $this
            ->setName('project:info')
            ->setDescription('Get info about a project')
            ->addArgument('id', InputArgument::REQUIRED, 'Project identifier (slug or id)');
    }

    /**
     * executes the command
     */
    protected function handle()
    {
        $client = $this->getClient();

        $command = new ShowCommand();
        $command->setId($this->argument('id'));

        try {
            $response = $client->send($command);
        } catch (ClientException $e) {
            if ($e->getCode() === 401) {
                $this->callSilent('dpm:token');

                $client->setToken($this->getApplication()->resetConfig()->config()->get('token'));

                $response = $client->send($command);
            } elseif ($e->getCode() === 404) {
                $this->info('No Project found');

                return 0;
            } else {
                $this->error($e->getMessage());

                return 1;
            }
        } catch (ServerException $e) {
            $message = json_decode($e->getResponse()->getBody()->getContents(), true);
            $detail = $message['detail'];
            $this->error($detail);

            return 1;
        }

        if ($response->getStatusCode() >= 300) {
            $this->info('No Project found');

            return 0;
        }

        $projects = json_decode($response->getBody()->getContents(), true);

        $project = $projects['data'];

        $table = new Table($this->output);
        $table->setHeaders(['Id', 'Slug', 'Project', 'Status']);

        $table->addRow([
            $project['id'],
            $project['attributes']['slug'],
            $project['attributes']['name'],
            $project['attributes']['status'],
        ]);

        $table->render();
    }
}