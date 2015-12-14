<?php

namespace DonePM\ConsoleClient\Http;

use DonePM\ConsoleClient\Http\Commands\Command;
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
     * @param \DonePM\ConsoleClient\Http\Commands\Command $command
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function send(Command $command)
    {
        return $this->client->send($command->request());
    }
}