<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Domain\Command;

use Arcella\Domain\Command\ResetPassword;

class ResetPasswordTest extends \PHPUnit_Framework_TestCase
{
    public function testCommand()
    {
        $newPassword = "newPassword";
        $token = "tok3n";
        $command = new ResetPassword($newPassword, $token);

        $this->assertEquals($newPassword, $command->newPassword());
        $this->assertEquals($token, $command->token());
    }
}
