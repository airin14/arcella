<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Domain\Command;

/**
 * This class is a Command that is used to change the email address of a given
 * user Entity.
 */
class UpdateUserEmail
{
    /**
     * The username of the User entity where the email should be updated.
     *
     * @var string $username
     */
    private $username;

    /**
     * @var string $email The new email address of the user.
     */
    private $email;

    /**
     * UpdateUserPassword constructor
     *
     * @param string $username The username of the User entity
     * @param string $email    The new Users email address
     */
    public function __construct($username, $email)
    {
        $this->username = $username;
        $this->email    = $email;
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
}
