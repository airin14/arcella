<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Domain\Event;

use Arcella\Domain\Entity\User;
use Arcella\Domain\Event\UserUpdatedPasswordEvent;

class UserUpdatedPasswordEventTest extends \PHPUnit_Framework_TestCase
{
    public function testonUserUpdatedPassword()
    {
        $username = "Username";

        $user = new User();
        $user->setUsername($username);

        $event = new UserUpdatedPasswordEvent($user);

        $this->assertEquals($username, $event->getUser()->getUsername());
    }

    public function testEventName()
    {
        $obj = new \ReflectionClass(UserUpdatedPasswordEvent::class);

        $this->assertEquals("user.updated.password", $obj->getConstant("NAME"));
    }
}
