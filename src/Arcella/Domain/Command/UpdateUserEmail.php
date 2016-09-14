<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Domain\Command;

/**
 * This class is a Command that is used to change the password of a user.
 *
 * @package Arcella\Application\Command
 */
class UpdateUserEmail
{
    /**
     * @var string $username The username where the password should be updated.
     */
    private $username;

    /**
     * @var string $email The new email address of the user.
     */
    private $email;

    /**
     * UpdateUserPassword constructor.
     * @param string $username
     * @param string $email
     */
    public function __construct($username, $email)
    {
        $this->username = $username;
        $this->email    = $email;
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
     * @return string
     */
    public function email()
    {
        return $this->email;
    }
}
