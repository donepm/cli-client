<?php

namespace DonePM\ConsoleClient\Http\Commands\Projects;

use DonePM\ConsoleClient\Http\Commands\Command;
use DonePM\ConsoleClient\Http\Commands\TokenizedCommand;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

/**
 * Class DeleteCommand
 *
 * Deletes project
 *
 * @package DonePM\ConsoleClient\Http\Commands\Projects
 */
class DeleteCommand extends TokenizedCommand implements Command
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
        return new Request('delete', self::PATH . $this->id, $this->header(), json_encode([
            'data' => [
                'type' => 'projects',
                'id' => $this->id,
            ]
        ]));
    }

    /**
     * set id
     *
     * @param string $id
     *
     * @return DeleteCommand
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}