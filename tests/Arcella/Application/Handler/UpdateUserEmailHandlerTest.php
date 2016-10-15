<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Application\Handler;

use Arcella\Application\Handler\UpdateUserEmailHandler;
use Arcella\Domain\Command\UpdateUserEmail;
use Arcella\Domain\Entity\User;
use Arcella\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateUserEmailHandlerTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

    public function testUpdateUserEmailHandler()
    {
        $username = "Username";
        $email = "foo@bar.com";

        $command = \Mockery::mock(UpdateUserEmail::class);
        $command->shouldReceive('username')->once()->andReturn($username);
        $command->shouldReceive('email')->once()->andReturn($email);

        $user = \Mockery::mock(User::class);
        $user->shouldReceive('setEmail')->once();
        $user->shouldReceive('setEmailIsVerified')->once();

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldReceive('save')->once();
        $userRepository->shouldReceive('findOneBy')->once()->andReturn($user);

            /*
             * Prepare Validation Violations (which is in this case an empty
             * array because there are none...)
             */
            $violations = new ConstraintViolationList(array());

        $validator = \Mockery::mock(ValidatorInterface::class);
        $validator->shouldReceive('validate')->once()->andReturn($violations);

        $eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldReceive('dispatch')->once();

        $handler = new UpdateUserEmailHandler($userRepository, $validator, $eventDispatcher);

        $handler->handle($command);
    }

    public function testUpdateUserEmailHandlerWithInvalidEntity()
    {
        $username = "Username";
        $email = "invalidEmailAddress";

        $command = \Mockery::mock(UpdateUserEmail::class);
        $command->shouldReceive('username')->once()->andReturn($username);
        $command->shouldReceive('email')->once()->andReturn($email);

        $user = \Mockery::mock(User::class);
        $user->shouldReceive('setEmail')->once();
        $user->shouldReceive('setEmailIsVerified')->once();

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldNotReceive('save');
        $userRepository->shouldReceive('findOneBy')->once()->andReturn($user);

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

        $handler = new UpdateUserEmailHandler($userRepository, $validator, $eventDispatcher);

        try {
            $handler->handle($command);
        } catch (ValidatorException $e) {
            $this->assertContains("Email is not a valid address", $e->getMessage());
        }
    }

    public function testUpdateUserEmailHandlerWithNonexistentUser()
    {
        $username = "Username";
        $email = "invalidEmailAddress";

        $command = \Mockery::mock(UpdateUserEmail::class);
        $command->shouldReceive('username')->twice()->andReturn($username);
        $command->shouldNotReceive('email');

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldNotReceive('save');
        $userRepository->shouldReceive('findOneBy')->once()->andReturn(false);

            /*
             * Prepare Validation Violations (which is in this case an empty
             * array because there are none...)
             */
            $violations = new ConstraintViolationList(array());

        $validator = \Mockery::mock(ValidatorInterface::class);
        $validator->shouldNotReceive('validate');

        $eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldNotReceive('dispatch');

        $handler = new UpdateUserEmailHandler($userRepository, $validator, $eventDispatcher);

        try {
            $handler->handle($command);
        } catch (EntityNotFoundException $e) {
            $this->assertContains("No entity found for username", $e->getMessage());
        }
    }
}
