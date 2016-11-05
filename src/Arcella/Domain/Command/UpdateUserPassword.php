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
 */
class UpdateUserPassword
{
    /**
     * The username of the User entity where the password should be updated
     *
     * @var string $username
     */
    private $username;

    /**
     * The old password of the user entity
     *
     * @var string $oldPassword
     */
    private $oldPassword;

    /**
     * The new password for the User entity
     *
     * @var string $newPassword
     */
    private $newPassword;

    /**
     * UpdateUserPassword constructor.
     *
     * @param string $username    The username of the User entity
     * @param string $oldPassword The current password of the User entity
     * @param string $newPassword The new password for the User entity
     */
    public function __construct($username, $oldPassword, $newPassword)
    {
        $this->username = $username;
        $this->oldPassword = $oldPassword;
        $this->newPassword = $newPassword;
    }

    /**
     * @return string $username
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * @return string $oldPassword
     */
    public function oldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @return string $newPassword
     */
    public function newPassword()
    {
        return $this->newPassword;
    }
}
