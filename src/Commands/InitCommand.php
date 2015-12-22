<?php

namespace DonePM\ConsoleClient\Commands;

use DonePM\ConsoleClient\Repositories\Config;
use DonePM\ConsoleClient\Repositories\Reader\JsonReader;
use DonePM\ConsoleClient\Repositories\Writer\JsonWriter;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InitCommand
 *
 * @package DonePM\ConsoleClient\Commands
 */
class InitCommand extends Command
{
    /**
     * configures the command
     */
    protected function configure()
    {
        $this
            ->setName('dpm:init')
            ->setDescription('Initializes local dpm account');
    }

    /**
     * executes the command
     */
    protected function handle()
    {
        $configFile = $this->getConfigFile();

        $config = $this->readConfigOrUseDefaultConfig($configFile);

        if ( ! $config->has('url')
            || ! $this->confirm('Is your donePM API url ' . $config->get('url') . '?', true)
        ) {
            $url = $this->ask('What is your donePM API url?', 'https://api.done.pm/');
            $config->set('url', $url);
        }

        if ( ! $config->has('email')
            || ! $this->confirm('Is your donePM email ' . $config->get('email') . '?', true)
        ) {
            $email = $this->ask('What is your donePM email?');
            $config->set('email', $email);
        }

        if ( ! $config->has('password')
            || ! $this->confirm('Do you want to keep your password?', true)
        ) {
            $password = $this->secret('What is your donePM password? (will be stored encrypted)');

            $key = $config->get('key', Str::random(16));
            $encrypter = new Encrypter($key);
            $config->set('password', $encrypter->encrypt($password));
            $config->set('key', $key);
        }

        if ( ! $config->has('token')) {
            // fetch token
            // $this->call('dpm:token');
        }

        (new JsonWriter())->write($configFile, $config);
    }

    /**
     * reads existing config or returns default config
     *
     * @param string $configFile
     *
     * @return \DonePM\ConsoleClient\Repositories\Config|\DonePM\ConsoleClient\Repositories\Repository
     */
    private function readConfigOrUseDefaultConfig($configFile)
    {

        if (file_exists($configFile)) {
            return (new JsonReader())->read($configFile);
        }

        return new Config();
    }
}