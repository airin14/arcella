<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Application\Handler;

use Arcella\Application\Handler\RegisterUserHandler;
use Arcella\Domain\Command\RegisterUser;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tests\Arcella\Application\Handler\MockEventDispatcher as EventDispatcher;
use Tests\Arcella\Application\Handler\MockUserRepository as UserRepository;

class RegisterUserHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterUserHandlerWithInvalidEntity()
    {
        $command = new RegisterUser("bar", "bar@foo.com", "arcella");

        try {
            $handler = $this->createHandler(false);
            $handler->handle($command);
        } catch (ValidatorException $e) {
            $this->assertContains("Username is not foo", $e->getMessage());
        }
    }

    public function testRegisterUserHandlerWithValidEntity()
    {
        $command = new RegisterUser("foo", "foo@bar.com", "arcella");

        $handler = $this->createHandler();
        $handler->handle($command);
    }

    private function createHandler($validation = true)
    {
        // 1. Create a mock of the UserRepository
        $mockUserRepository = new UserRepository();

        // 2. Create a stub of the Validator
        if ($validation == true)
        {
            $validations = new ConstraintViolationList(array());
        }
        elseif ($validation == false)
        {
            $message = new ConstraintViolation("Username is not foo", "Username is not foo", array(), "foo", "testuser", "foo");
            $validations = new ConstraintViolationList(array($message));
        }

        $stubValidator = $this->createMock(ValidatorInterface::class);
        $stubValidator->method('validate')
            ->willReturn($validations);

        // 3. Create a mock of the EventDispatcher
        $mockEventDispatcher = new EventDispatcher();

        // 4. Settings for the Salt
        $salt_length = 6;
        $salt_keyspace = "0123456789";

        $handler = new RegisterUserHandler($mockUserRepository, $stubValidator, $mockEventDispatcher, $salt_length, $salt_keyspace);

        return $handler;
    }
}
