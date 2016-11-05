<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Domain\Event;

use Arcella\Domain\Entity\User;
use Arcella\Domain\Event\RecoverPasswordEvent;
use Arcella\Domain\Event\UserRegisteredEvent;

class RecoverPasswordEventTest extends \PHPUnit_Framework_TestCase
{
    public function testonRecoverPassword()
    {
        $username = "Username";

        $user = new User();
        $user->setUsername($username);

        $event = new RecoverPasswordEvent($user);

        $this->assertEquals($username, $event->getUser()->getUsername());
    }

    public function testEventName()
    {
        $obj = new \ReflectionClass(RecoverPasswordEvent::class);

        $this->assertEquals("user.password.recover", $obj->getConstant("NAME"));
    }
}