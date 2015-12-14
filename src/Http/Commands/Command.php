<?php

namespace DonePM\ConsoleClient\Http\Commands;

use Psr\Http\Message\RequestInterface;

/**
 * Interface Command
 *
 * @package DonePM\ConsoleClient\Http\Commands
 */
interface Command
{
    /**
     * @return RequestInterface
     */
    public function request();
}