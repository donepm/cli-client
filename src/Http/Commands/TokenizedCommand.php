<?php

namespace DonePM\ConsoleClient\Http\Commands;

/**
 * Class TokenizedCommand
 *
 * @package DonePM\ConsoleClient\Http\Commands
 */
abstract class TokenizedCommand implements Command, NeedsToken
{
    /**
     * token
     *
     * @var string
     */
    private $token;

    /**
     * sets or fetches token
     *
     * @param string|null $token
     *
     * @return static|Needstoken|$this|string
     */
    public function token($token = null)
    {
        if (null === $token) {
            return $this->token;
        }

        $this->token = $token;

        return $this;
    }

    /**
     * returns default header, with optional additional headers
     *
     * @param array $additionalHeaders
     *
     * @return array
     */
    public function header(array $additionalHeaders = [])
    {
        return array_merge([
            'Authorization' => 'Bearer ' . $this->token(),
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json'
        ], $additionalHeaders);
    }
}