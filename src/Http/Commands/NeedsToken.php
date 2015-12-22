<?php

namespace DonePM\ConsoleClient\Http\Commands;

/**
 * Interface NeedsToken
 *
 * @package DonePM\ConsoleClient\Http\Commands
 */
interface NeedsToken
{
    /**
     * sets or fetches token
     *
     * @param string|null $token
     *
     * @return static|Needstoken|$this|string
     */
    public function token($token = null);
}