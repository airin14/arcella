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
     * @var string The new user entity.
     */
    private $user;

    /**
     * RegisterUser constructor.
     *
     * @param User $user The new user entity
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Returns the $user entity.
     *
     * @return string $user
     */
    public function user()
    {
        return $this->user;
    }
}
