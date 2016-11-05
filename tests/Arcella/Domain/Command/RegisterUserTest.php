<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Domain\Command;

use Arcella\Domain\Command\RegisterUser;

class RegisterUserTest extends \PHPUnit_Framework_TestCase
{
    public function testCommand()
    {
        $username = "Username";
        $email = "foo@bar.com";
        $password = "Password";

        $command = new RegisterUser($username, $email, $password);

        $this->assertEquals($username, $command->username());
        $this->assertEquals($email, $command->email());
        $this->assertEquals($password, $command->password());
    }
}
