<?php

namespace DonePM\ConsoleClient\Repositories\Reader;

use DonePM\ConsoleClient\Repositories\Repository;

/**
 * Interface Reader
 *
 * @package DonePM\ConsoleClient\Repositories\Reader
 */
interface Reader
{
    /**
     * reads file into repository
     *
     * @param string $file
     *
     * @return Repository
     */
    public function read($file);
}