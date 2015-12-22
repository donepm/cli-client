<?php

namespace DonePM\ConsoleClient\Commands\Projects;

use DonePM\ConsoleClient\Commands\Command;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class DeleteCommand
 *
 * Delete a project
 *
 * @package DonePM\ConsoleClient\Commands\Projects
 */
class DeleteCommand extends Command
{
    /**
     * configures the command
     */
    protected function configure()
    {
        $this
            ->setName('project:delete')
            ->setDescription('Deletes a project')
            ->addArgument('id', InputArgument::REQUIRED, 'Project id');
    }

    /**
     * handles project creation
     */
    protected function handle()
    {
        $client = $this->getClient();

        $command = new \DonePM\ConsoleClient\Http\Commands\Projects\DeleteCommand();
        $command->setId($this->argument('id'));

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
        } catch (ServerException $e) {
            $message = json_decode($e->getResponse()->getBody()->getContents(), true);
            $detail = $message['detail'];
            $this->error($detail);

            return;
        }

        if ($response->getStatusCode() === 204) {
            $this->info('Project deleted');

            return;
        }

        $this->error('Something went wrong');
    }

}