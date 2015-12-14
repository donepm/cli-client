<?php

namespace DonePM\ConsoleClient\Commands;

use DonePM\ConsoleClient\Http\Client;
use DonePM\ConsoleClient\Http\Commands\LoginCommand;
use DonePM\ConsoleClient\Http\Request;
use DonePM\ConsoleClient\Repositories\Reader\JsonReader;
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
        $configFile = $this->getConfigFile();

        try {
            $config = $this->readConfig($configFile);
        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());
            $this->info('Please set your configuration first.');

            $this->call('dpm:init');

            $config = $this->readConfig($configFile);
        }

        Request::$API_URL = $config->get('url');

        $httpClient = new Client();

        $email = $config->get('email');
        $key = $config->get('key');
        $encrypter = new Encrypter($key);
        $password = $encrypter->decrypt($config->get('password'));

        $response = $httpClient->send(new LoginCommand($email, $password));

        var_dump($response);
    }

    /**
     * reads existing config
     *
     * @param string $configFile
     *
     * @return \DonePM\ConsoleClient\Repositories\Config|\DonePM\ConsoleClient\Repositories\Repository
     *
     * @throws \RuntimeException when no config file is found
     */
    private function readConfig($configFile)
    {
        if ( ! file_exists($configFile)) {
            throw new \RuntimeException("No config file $configFile found");
        }

        return (new JsonReader())->read($configFile);
    }
}