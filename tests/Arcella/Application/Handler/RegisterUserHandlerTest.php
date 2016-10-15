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
use Arcella\Domain\Repository\UserRepositoryInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RegisterUserHandlerTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

    public function testRegisterUserHandler()
    {
        $username = "Username";
        $email = "foo@bar.com";
        $password = "Password";

        $command = \Mockery::mock(RegisterUser::class);
        $command->shouldReceive('username')->once()->andReturn($username);
        $command->shouldReceive('email')->once()->andReturn($email);
        $command->shouldReceive('password')->once()->andReturn($password);

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldReceive('add')->once();

        /*
         * Prepare Validation Violations (which is in this case an empty
         * array because there are none...)
         * */
        $violations = new ConstraintViolationList(array());

        $validator = \Mockery::mock(ValidatorInterface::class);
        $validator->shouldReceive('validate')->once()->andReturn($violations);

        $eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldReceive('dispatch')->once();

        $salt_length = 6;
        $salt_keyspace = "0123456789";

        $handler = new RegisterUserHandler($userRepository, $validator, $eventDispatcher, $salt_length, $salt_keyspace);

        $handler->handle($command);
    }

    public function testRegisterUserHandlerWithInvalidEntity()
    {
        $username = "Username";
        $email = "invalidEmail";
        $password = "Password";

        $command = \Mockery::mock(RegisterUser::class);
        $command->shouldReceive('username')->once()->andReturn($username);
        $command->shouldReceive('email')->once()->andReturn($email);
        $command->shouldReceive('password')->once()->andReturn($password);

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldNotReceive('add');

            // Prepare Validation Violations
            $messages = new ConstraintViolation(
                "Email is not a valid address",
                "Email is not a valid address",
                array(),
                $email,
                "email",
                $email
            );

            $violations = new ConstraintViolationList(array($messages));

        $validator = \Mockery::mock(ValidatorInterface::class);
        $validator->shouldReceive('validate')->once()->andReturn($violations);

        $eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldNotReceive('dispatch');

        $salt_length = 6;
        $salt_keyspace = "0123456789";

        $handler = new RegisterUserHandler($userRepository, $validator, $eventDispatcher, $salt_length, $salt_keyspace);

        try {
            $handler->handle($command);
        } catch (ValidatorException $e) {
            $this->assertContains("Email is not a valid address", $e->getMessage());
        }
    }
}
