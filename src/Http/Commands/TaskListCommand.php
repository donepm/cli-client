<?php

namespace DonePM\ConsoleClient\Http\Commands;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

/**
 * Class TaskListCommand
 *
 * @package DonePM\ConsoleClient\Http\Commands
 */
class TaskListCommand extends TokenizedCommand implements Command
{
    const PATH = '/api/v1/tasks';

    /**
     * @return RequestInterface
     */
    public function request()
    {
        return new Request('get', self::PATH, $this->header());
    }
}