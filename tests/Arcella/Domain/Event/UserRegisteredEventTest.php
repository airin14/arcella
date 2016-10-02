<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\Arcella\Domain\Event;

use Arcella\Domain\Entity\User;
use Arcella\Domain\Event\UserRegisteredEvent;

class UserRegisteredEventTest extends \PHPUnit_Framework_TestCase
{
    public function testonUserRegistered()
    {
        $username = "Username";

        $user = new User();
        $user->setUsername($username);

        $event = new UserRegisteredEvent($user);

        $this->assertEquals($username, $event->getUser()->getUsername());
    }

    public function testEventName()
    {
        $obj = new \ReflectionClass(UserRegisteredEvent::class);

        $this->assertEquals("user.registered", $obj->getConstant("NAME"));
    }
}