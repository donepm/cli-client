<?php

namespace DonePM\ConsoleClient\Http\Commands\Projects;

use DonePM\ConsoleClient\Http\Commands\Command;
use DonePM\ConsoleClient\Http\Commands\TokenizedCommand;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

/**
 * Class ShowCommand
 *
 * @package DonePM\ConsoleClient\Http\Commands
 */
class ShowCommand extends TokenizedCommand implements Command
{
    const PATH = '/api/v1/projects/';

    /**
     * project id
     *
     * @var integer
     */
    private $id;

    /**
     * @return RequestInterface
     */
    public function request()
    {
        return new Request('get', self::PATH . $this->id, $this->header());
    }

    /**
     * set id
     *
     * @param int $id
     *
     * @return ShowCommand
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}