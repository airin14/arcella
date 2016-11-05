<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Domain\Command;

/**
 * This class is a Command that is used to reset the password of a user.
 */
class ResetPassword
{
    /**
     * The new password for the User entity
     *
     * @var string $newPassword
     */
    private $newPassword;

    /**
     * @var string $token
     */
    private $token;

    /**
     * UpdateUserPassword constructor.
     *
     * @param string $newPassword The new password for the User entity
     * @param string $token       The token that is to be validated
     */
    public function __construct($newPassword, $token)
    {
        $this->newPassword = $newPassword;
        $this->token = $token;
    }

    /**
     * @return string $newPassword
     */
    public function newPassword()
    {
        return $this->newPassword;
    }

    /**
     * @return string $token
     */
    public function token()
    {
        return $this->token;
    }
}
