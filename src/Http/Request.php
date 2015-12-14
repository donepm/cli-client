<?php

namespace DonePM\ConsoleClient\Http;

/**
 * Class Request
 *
 * @package DonePM\ConsoleClient\Http
 */
class Request extends \GuzzleHttp\Psr7\Request
{
    /**
     * headers array
     *
     * @var array
     */
    protected $_headers = [
        'Accept' => 'application/vnd.api+json',
        'Content-Type' => 'application/vnd.api+json',
        'User-Agent' => 'donePM-Cli-Client/0.0.1-alpha',
    ];

    /**
     * api url
     *
     * @var string
     */
    public static $API_URL = 'https://api.done.pm/v1';

    /**
     * constructing Request
     *
     * @param null|string $method
     * @param null|\Psr\Http\Message\UriInterface|string $uri
     * @param null $body
     * @param array $headers
     */
    public function __construct($method, $uri, $body = null, array $headers = [])
    {
        $headers = array_merge($this->_headers, $headers);

        parent::__construct($method, rtrim(static::$API_URL, '/') . '/' . ltrim($uri, '/'), $headers, $body);
    }
}