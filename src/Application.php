<?php

namespace DonePM\ConsoleClient;

/**
 * Class Application
 *
 * @package DonePM\ConsoleClient
 */
class Application extends \Symfony\Component\Console\Application
{
    const VERSION = '0.0.1';

    /**
     * Application constructor.
     */
    public function __construct()
    {
        parent::__construct('donepm cli', self::VERSION);
    }
}