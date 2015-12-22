<?php

namespace DonePM\ConsoleClient\Http\Commands\Tasks;

use DonePM\ConsoleClient\Http\Commands\Command;
use DonePM\ConsoleClient\Http\Commands\TokenizedCommand;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

/**
 * Class IndexCommand
 *
 * @package DonePM\ConsoleClient\Http\Commands\Tasks
 */
class IndexCommand extends TokenizedCommand implements Command
{
    const PATH = '/api/v1/tasks?include=project';

    /**
     * @return RequestInterface
     */
    public function request()
    {
        return new Request('get', self::PATH, $this->header());
    }
}