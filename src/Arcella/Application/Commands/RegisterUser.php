<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Application\Commands;

/**
 * Class RegisterUser
 * @package Arcella\Application\Commands
 */
class RegisterUser
{
    /**
     * @var string The new users name
     */
    private $username;

    /**
     * @var array The new users roles
     */
    private $roles;

    /**
     * @var string The new users password (in plaintext)
     */
    private $password;

    /**
     * @var string The new users salt
     */
    private $salt;

    /**
     * RegisterUser constructor.
     * @param string $username The new users name
     * @param array  $roles    The new users roles
     * @param string $password The new users password (in plaintext)
     * @param string $salt     The new users salt
     */
    public function __construct($username, $roles, $password, $salt)
    {
        $this->username = $username;
        $this->roles = $roles;
        $this->password = $password;
        $this->salt = $salt;
    }

    /**
     * @return string The new users name
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * @return array The new users roles
     */
    public function roles()
    {
        return $this->roles;
    }

    /**
     * @return string The new users password (in plaintext)
     */
    public function password()
    {
        return $this->password;
    }

    /**
     * @return string The new users salt
     */
    public function salt()
    {
        return $this->salt;
    }
}
