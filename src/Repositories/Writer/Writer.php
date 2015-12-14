<?php

namespace DonePM\ConsoleClient\Repositories\Writer;

use DonePM\ConsoleClient\Repositories\Repository;

/**
 * Interface Writer
 *
 * @package DonePM\ConsoleClient\Repositories\Writer
 */
interface Writer
{
    /**
     * writes repository data
     *
     * @param string $file
     * @param \DonePM\ConsoleClient\Repositories\Repository $repository
     *
     * @return bool
     */
    public function write($file, Repository $repository);
}