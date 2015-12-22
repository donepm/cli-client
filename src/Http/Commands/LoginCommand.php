<?php

namespace DonePM\ConsoleClient\Http\Commands;

use DonePM\ConsoleClient\Http\Request;
use Psr\Http\Message\RequestInterface;

/**
 * Class LoginCommand
 *
 * Prepares the login request
 *
 * @package DonePM\ConsoleClient\Http\Commands
 */
class LoginCommand implements Command
{
    const PATH = '/auth/login';

    /**
     * email
     *
     * @var string
     */
    private $email;

    /**
     * password
     *
     * @var string
     */
    private $password;

    /**
     * LoginCommand constructor.
     *
     * @param string $email
     * @param string $password
     */
    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return RequestInterface
     */
    public function request()
    {
        return new Request('post', self::PATH, sprintf('username=%s&password=%s', $this->email, $this->password), [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ]);
    }
}