<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Domain\Event;

use Arcella\Domain\Entity\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * This class is an Event that is fired whenever a new User tries to recover
 * the password.
 */
class RecoverPasswordEvent extends Event
{
    /**
     * @const NAME Caption of the event.
     */
    const NAME = 'user.password.recover';

    /**
     * @var User $user The newly created user.
     */
    protected $user;

    /**
     * UserRegisteredEvent constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Returns the new $user entity.
     *
     * @return User $user
     */
    public function getUser()
    {
        return $this->user;
    }
}