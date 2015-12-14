<?php

namespace DonePM\ConsoleClient\Repositories\Writer;

use DonePM\ConsoleClient\Repositories\Repository;

/**
 * Class JsonWriter
 *
 * @package DonePM\ConsoleClient\Repositories\Writer
 */
class JsonWriter implements Writer
{
    /**
     * writes repository data
     *
     * @param string $file
     * @param \DonePM\ConsoleClient\Repositories\Repository $repository
     *
     * @return bool
     */
    public function write($file, Repository $repository)
    {
        $directory = pathinfo($file, PATHINFO_DIRNAME);

        if ( ! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents($file, json_encode($repository->all()));

        return true;
    }
}