<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Domain\Command;

/**
 * This class is a Command that is used to register a new User to the system.
 */
class RegisterUser
{
    /**
     * @var string The new Users name
     */
    private $username;

    /**
     * @var string $email The new Users email address
     */
    private $email;

    /**
     * @var string $password The new Users password in plaintext
     */
    private $password;

    /**
     * RegisterUser constructor
     *
     * @param string $username The new Users name
     * @param string $email    The new Users email address
     * @param string $password The new Users password in plaintext
     */
    public function __construct($username, $email, $password)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return string $username
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * @return string $email
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * @return string $password
     */
    public function password()
    {
        return $this->password;
    }
}
