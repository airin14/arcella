<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Domain\Command;

use Arcella\Domain\Entity\User;

/**
 * This class is a Command that is used to register a new User to the system.
 *
 * @package Arcella\Application\Command
 */
class RegisterUser
{
    /**
     * @var string The new users name.
     */
    private $username;

    private $email;

    private $password;

    /**
     * RegisterUser constructor.
     * @param $username
     * @param $email
     * @param $password
     */
    public function __construct($username, $email, $password)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Returns the $username.
     *
     * @return string $username
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function password()
    {
        return $this->password;
    }
}
