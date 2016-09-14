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
class UpdateUserPassword
{
    /**
     * @var string $username The username where the password should be updated.
     */
    private $username;

    /**
     * @var string $oldPassword The old password of the user.
     */
    private $oldPassword;

    /**
     * @var string $newPassword The new password of the user.
     */
    private $newPassword;

    /**
     * UpdateUserPassword constructor.
     * @param string $username
     * @param string $oldPassword
     * @param string $newPassword
     */
    public function __construct($username, $oldPassword, $newPassword)
    {
        $this->username = $username;
        $this->oldPassword = $oldPassword;
        $this->newPassword = $newPassword;
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
    public function oldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @return string
     */
    public function newPassword()
    {
        return $this->newPassword;
    }
}
