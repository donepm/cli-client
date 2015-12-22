<?php

namespace DonePM\ConsoleClient;

use DonePM\ConsoleClient\Commands\InitCommand;
use DonePM\ConsoleClient\Commands\RollbackCommand;
use DonePM\ConsoleClient\Commands\SelfUpdateCommand;
use DonePM\ConsoleClient\Commands\TokenCommand;
use DonePM\ConsoleClient\Repositories\Config;
use DonePM\ConsoleClient\Repositories\Reader\JsonReader;
use DonePM\ConsoleClient\Repositories\Writer\JsonWriter;
use Symfony\Component\Console\Command\Command;

/**
 * Class Application
 *
 * @package DonePM\ConsoleClient
 */
class Application extends \Symfony\Component\Console\Application
{
    const VERSION = '1.0.0';

    const API_URL = 'https://api.done.pm/';

    /**
     * config file
     *
     * @var string
     */
    private $configFile;

    /**
     * configuration
     *
     * @var Config
     */
    private $config;

    /**
     * Application constructor.
     *
     * @param string|null $configFile
     */
    public function __construct($configFile = null)
    {
        parent::__construct('donepm cli', self::VERSION);

        $this->configFile = $configFile ?: $this->getDirectoryInHomeDirectory('.dpm/config');
    }

    /**
     * returns configuration
     *
     * @return \DonePM\ConsoleClient\Repositories\Config|\DonePM\ConsoleClient\Repositories\Repository
     */
    public function config()
    {
        if (null === $this->config) {
            $this->config = $this->readConfigOrUseDefaultConfig();
        }

        return $this->config;
    }

    /**
     * resets config
     *
     * @return $this
     */
    public function resetConfig()
    {
        $this->config = null;

        return $this;
    }

    /**
     * writes config
     *
     * @param \DonePM\ConsoleClient\Repositories\Config $config
     *
     * @return $this
     */
    public function writeConfig(Config $config)
    {
        (new JsonWriter())->write($this->getConfigFile(), $config);

        return $this;
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return Command[] An array of default Command instances
     */
    protected function getDefaultCommands()
    {
        return array_merge(parent::getDefaultCommands(), [
            new InitCommand(),
            new TokenCommand(),
            new Commands\Projects\ListCommand(),
            new Commands\Projects\InfoCommand(),
            new Commands\Projects\CreateCommand(),
            new Commands\Projects\DeleteCommand(),

            new Commands\Tasks\ListCommand(),
            new Commands\Tasks\CreateCommand(),

            new SelfUpdateCommand(),
            new RollbackCommand(),
        ]);
    }

    /**
     * returns config file
     *
     * @return string
     */
    private function getConfigFile()
    {
        return $this->configFile;
    }

    /**
     * returns directory path within home directory
     *
     * @param string $path
     *
     * @return string
     */
    private function getDirectoryInHomeDirectory($path)
    {
        if (isset($_SERVER['HOME'])) {
            return $_SERVER['HOME'] . DIRECTORY_SEPARATOR . $path;
        }

        return $_SERVER['HOMEDRIVE'] . DIRECTORY_SEPARATOR . $_SERVER['HOMEPATH'] . DIRECTORY_SEPARATOR . $path;
    }

    /**
     * reads existing config or returns default config
     *
     * @return \DonePM\ConsoleClient\Repositories\Config|\DonePM\ConsoleClient\Repositories\Repository
     */
    private function readConfigOrUseDefaultConfig()
    {
        if (file_exists($this->getConfigFile())) {
            return (new JsonReader())->read($this->getConfigFile());
        }

        return new Config();
    }
}