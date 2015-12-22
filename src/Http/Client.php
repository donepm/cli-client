<?php

namespace DonePM\ConsoleClient\Http;

use DonePM\ConsoleClient\Http\Commands\Command;
use DonePM\ConsoleClient\Http\Commands\NeedsToken;
use GuzzleHttp\ClientInterface;

/**
 * Class Client
 *
 * @package DonePM\ConsoleClient\Http
 */
class Client
{
    /**
     * http client
     *
     * @var \GuzzleHttp\Client|\GuzzleHttp\ClientInterface
     */
    private $client;

    /**
     * access token
     *
     * @var string
     */
    private $token;

    /**
     * constructing Client
     *
     * @param \GuzzleHttp\ClientInterface $client
     */
    public function __construct(ClientInterface $client = null)
    {
        if (null === $client) {
            $client = new \GuzzleHttp\Client();
        }

        $this->client = $client;
    }

    /**
     * sets token
     *
     * @param string $token
     *
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @param \DonePM\ConsoleClient\Http\Commands\Command $command
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function send(Command $command)
    {
        if ($command instanceof NeedsToken) {
            $command->token($this->token);
        }

        return $this->client->send($command->request());
    }
}