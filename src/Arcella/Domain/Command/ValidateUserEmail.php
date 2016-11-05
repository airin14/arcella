<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Domain\Command;

/**
 * This class is a Command that is used to validate a changed user email
 * address of a given user Entity.
 */
class ValidateUserEmail
{
    /**
     * The username of the User entity where the email was be updated.
     *
     * @var string $username
     */
    private $username;

    /**
     * @var string $token
     */
    private $token;

    /**
     * UpdateUserPassword constructor
     *
     * @param string $username The username of the User entity
     * @param string $token    The token that is to be validated
     */
    public function __construct($username, $token)
    {
        $this->username = $username;
        $this->token    = $token;
    }

    /**
     * @return string $username
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * @return string $token
     */
    public function token()
    {
        return $this->token;
    }
}
