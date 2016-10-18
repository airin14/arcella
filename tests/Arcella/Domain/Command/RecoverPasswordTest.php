<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Domain\Command;

use Arcella\Domain\Command\RecoverPassword;

class RecoverPasswordTest extends \PHPUnit_Framework_TestCase
{
    public function testCommand()
    {
        $email = "foo@bar.com";
        $command = new RecoverPassword($email);

        $this->assertEquals($email, $command->email());
    }
}
