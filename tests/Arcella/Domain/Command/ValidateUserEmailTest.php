<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Domain\Command;

use Arcella\Domain\Command\ValidateUserEmail;

class ValidateUserEmailTest extends \PHPUnit_Framework_TestCase
{
    public function testCommand()
    {
        $username = "Username";
        $token = "T0K3N";

        $command = new ValidateUserEmail($username, $token);

        $this->assertEquals($username, $command->username());
        $this->assertEquals($token, $command->token());
    }
}
