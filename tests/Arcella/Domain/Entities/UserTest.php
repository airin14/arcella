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
        $username = "TestUser";

        $user = new User();
        $user->setUsername($username);

        $this->assertEquals($username, $user->getUsername());
    }

    public function testSetRoles()
    {
        $roles = "ROLE_USER";

        $user = new User();
        $user->setRoles($roles);

        $this->assertEquals($roles, $user->getRoles());
    }

    public function testSetPassword()
    {
        $password = "foobar";

        $user = new User();
        $user->setPassword($password);

        $this->assertEquals($password, $user->getPassword());
    }

    public function testSetSalt()
    {
        $salt = "salt";

        $user = new User();
        $user->setSalt($salt);

        $this->assertEquals($salt, $user->getSalt());
    }
}
