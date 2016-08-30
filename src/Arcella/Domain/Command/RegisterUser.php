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
 * Class RegisterUser
 * @package Arcella\Application\Commands
 */
class RegisterUser
{
    /**
     * @var string The new user
     */
    private $user;

    /**
     * RegisterUser constructor.
     * @param string $user The new user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string The new users name
     */
    public function user()
    {
        return $this->user;
    }
}
