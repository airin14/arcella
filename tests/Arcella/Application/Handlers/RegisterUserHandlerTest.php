<?php
/**
 * Created by PhpStorm.
 * User: nplhse
 * Date: 17.08.16
 * Time: 21:31
 */

namespace Arcella\Application\Handlers;

use Arcella\Application\Commands\RegisterUser;
use Arcella\Application\Handlers\RegisterUserHandler;

class RegisterUserHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterUserHandler()
    {
        $command = new RegisterUser("Test", "ROLE_TEST", "Password", "Salt");

        $handler = new RegisterUserHandler();
        $handler->handle($command);
    }
}
