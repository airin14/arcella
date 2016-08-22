<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Application\Handlers;

use Arcella\Domain\Entity\User;
use Arcella\Application\Commands\RegisterUser;

/**
 * Class RegisterUserHandler
 * @package Arcella\Application\Handlers
 */
class RegisterUserHandler
{
    /**
     * @param RegisterUser $command
     */
    public function handle(RegisterUser $command)
    {
        $user = new User();
        $user->setUsername($command->username());
        $user->setRoles($command->roles());
        $user->setPassword($command->password());
        $user->setSalt($command->salt());
    }
}
