<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Domain\Event;

use Symfony\Component\EventDispatcher\Event;
use Arcella\Domain\Entity\User;

/**
 * Class UserRegisteredEvent
 * @package Arcella\Domain\Event
 */
class UserRegisteredEvent extends Event
{
    /**
     *
     */
    const NAME = 'user.registered';

    /**
     * @var User
     */
    protected $user;

    /**
     * UserRegisteredEvent constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
