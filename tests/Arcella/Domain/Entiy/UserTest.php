<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Domain\Entity;

use Arcella\Domain\Entity\User;
use Arcella\Domain\Exception\DomainException;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var User $user
     */
    private $user;

    public function setUp()
    {
        $this->user = new User();
    }

    public function testUsername()
    {
        $username = "TestUser";
        $this->user->setUsername($username);

        $this->assertEquals($username, $this->user->getUsername());
    }

    public function testValidRoles()
    {
        $roles = array('ROLE_ADMIN', 'ROLE_USER');
        $this->user->setRoles($roles);

        $this->assertEquals($roles, $this->user->getRoles());
    }

    public function testInvalidRoles()
    {
        try {
            $roles = "IamNotAnArray";
            $this->user->setRoles($roles);
        } catch (DomainException $e) {
            $this->assertEquals($e->getMessage(), "Roles must be an array");
        }
    }

    public function testPassword()
    {
        $password = "foobar";
        $this->user->setPassword($password);

        $this->assertEquals($password, $this->user->getPassword());
    }

    public function testSalt()
    {
        $salt = "salt";
        $this->user->setSalt($salt);

        $this->assertEquals($salt, $this->user->getSalt());
    }

    public function testEmail()
    {
        $email = "foo@bar.tld";
        $this->user->setEmail($email);

        $this->assertEquals($email, $this->user->getEmail());
    }
}
