<?php

namespace DonePM\ConsoleClient\Http\Commands\Projects;

use DonePM\ConsoleClient\Http\Commands\Command;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

/**
 * Class RelatedTasksCommand
 *
 * @package DonePM\ConsoleClient\Http\Commands\Projects
 */
class RelatedTasksCommand extends ShowCommand implements Command
{
    const PATH = '/api/v1/projects/';
    const PATH_RELATION = '/relationships/tasks?include=project';

    /**
     * @return RequestInterface
     */
    public function request()
    {
        return new Request('get', self::PATH . $this->id . self::PATH_RELATION, $this->header());
    }
}