<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Domain\Entities;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testSetUsername()
    {
        $name = "TestUser";

        $user = new User();
        $user->setName = $name;

        $this->assertEquals($name, $user->getName());
    }
}
