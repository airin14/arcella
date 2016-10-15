<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Domain\Event;

use Arcella\Domain\Entity\User;
use Arcella\Domain\Event\UserUpdatedEmailEvent;

class UserUpdatedEmailEventTest extends \PHPUnit_Framework_TestCase
{
    public function testonUserUpdatedEmail()
    {
        $email = "foo@bar.com";

        $user = new User();
        $user->setEmail($email);

        $event = new UserUpdatedEmailEvent($user);

        $this->assertEquals($email, $event->getUser()->getEmail());
    }

    public function testEventName()
    {
        $obj = new \ReflectionClass(UserUpdatedEmailEvent::class);

        $this->assertEquals("user.updated.email", $obj->getConstant("NAME"));
    }
}
