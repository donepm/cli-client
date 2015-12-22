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
        if ( ! is_numeric($this->argument('id'))) {
            $this->error('Argument id has to be numeric');

            return 1;
        }

        $client = $this->getClient();

        $command = new \DonePM\ConsoleClient\Http\Commands\Projects\DeleteCommand();
        $command->setId($this->argument('id'));

        try {
            $response = $client->send($command);
        } catch (ClientException $e) {
            if ($e->getCode() === 401) {
                $this->callSilent('dpm:token');

                $client->setToken($this->getApplication()->resetConfig()->config()->get('token'));

                $response = $client->send($command);
            } elseif ($e->getCode() === 404) {
                $this->info('Project does not exist');

                return 0;
            } else {
                $this->error($e->getMessage());

                return 0;
            }
        } catch (ServerException $e) {
            $message = json_decode($e->getResponse()->getBody()->getContents(), true);
            $detail = $message['detail'];
            $this->error($detail);

            return 1;
        }

        if ($response->getStatusCode() === 204) {
            $this->info('Project deleted');

            return 0;
        }

        $this->error('Something went wrong');

        return 1;
    }

}