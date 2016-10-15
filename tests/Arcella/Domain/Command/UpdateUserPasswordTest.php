<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Domain\Command;

use Arcella\Domain\Command\UpdateUserPassword;

class UpdateUserPasswordTest extends \PHPUnit_Framework_TestCase
{
    public function testCommand()
    {
        $username = "Username";
        $oldPassword = "Password";
        $newPassword = "P4SSW0RD";

        $command = new UpdateUserPassword($username, $oldPassword, $newPassword);

        $this->assertEquals($username, $command->username());
        $this->assertEquals($oldPassword, $command->oldPassword());
        $this->assertEquals($newPassword, $command->newPassword());
    }
}
