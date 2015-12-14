<?php

namespace DonePM\ConsoleClient\Repositories\Reader;

use DonePM\ConsoleClient\Repositories\Config;
use DonePM\ConsoleClient\Repositories\Repository;

/**
 * Class JsonReader
 *
 * @package DonePM\ConsoleClient\Repositories\Reader
 */
class JsonReader implements Reader
{
    /**
     * reads file into repository
     *
     * @param string $file
     *
     * @return Repository
     */
    public function read($file)
    {
        if ( ! is_readable($file)) {
            throw new \InvalidArgumentException("File $file is not readable");
        }

        return new Config(json_decode(file_get_contents($file), true));
    }
}