<?php

namespace DonePM\ConsoleClient\Http\Commands;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

/**
 * Class ProjectListCommand
 *
 * @package DonePM\ConsoleClient\Http\Commands
 */
class ProjectListCommand implements Command
{
    const PATH = '/api/v1/projects';
    /**
     *
     *
     * @var
     */
    private $token;

    /**
     * constructing ProjectListCommand
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * @return RequestInterface
     */
    public function request()
    {
        return new Request('get', 'http://192.168.33.10/'.self::PATH, ['Authorization' => 'Bearer ' . $this->token, 'Accept' => 'application/vnd.api+json', 'Content-Type' => 'application/vnd.api+json']);
    }
}