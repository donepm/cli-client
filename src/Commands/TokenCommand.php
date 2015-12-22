<?php

namespace DonePM\ConsoleClient\Commands;

use DonePM\ConsoleClient\Http\Client;
use DonePM\ConsoleClient\Http\Commands\LoginCommand;
use DonePM\ConsoleClient\Http\Request;
use DonePM\ConsoleClient\Repositories\Reader\JsonReader;
use DonePM\ConsoleClient\Repositories\Writer\JsonWriter;
use Illuminate\Encryption\Encrypter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TokenCommand
 *
 * @package DonePM\ConsoleClient\Commands
 */
class TokenCommand extends Command
{
    /**
     * configures the command
     */
    protected function configure()
    {
        $this
            ->setName('dpm:token')
            ->setDescription('Fetches a fresh token');
    }

    /**
     * executes the command
     */
    protected function handle()
    {
        $config = $this->getApplication()->config();

        $httpClient = $this->getClient();

        $email = $config->get('email');
        $key = $config->get('key');

        $password = (new Encrypter($key))->decrypt($config->get('password'));

        $response = $httpClient->send(new LoginCommand($email, $password));

        if ($response->getStatusCode() === 200) {
            $tokenResponse = $response->getBody()->getContents();

            $token = json_decode($tokenResponse, true);

            if (array_key_exists('token', $token)) {
                $config->set('token', $token['token']);

                $this->getApplication()->writeConfig($config);

                $this->info('You are logged in');

                return;
            }
        }

        $this->error('You are not logged in yet');
    }
}