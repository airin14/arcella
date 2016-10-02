<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Application\Handler;

use Arcella\Application\Handler\UpdateUserPasswordHandler;
use Arcella\Domain\Command\UpdateUserPassword;
use Arcella\UserBundle\Entity\User;
use Arcella\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Validator\Exception\ValidatorException;

class UpdateUserPasswordHandlerTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

    public function testUpdateUserPasswordHandler()
    {
        $username = "Username";
        $oldPassword = "Password";
        $newPassword = "NewPassword";

        $command = new UpdateUserPassword($username, $oldPassword, $newPassword);

        $user = \Mockery::mock(User::class);
        $user->shouldReceive('setPlainPassword')->once()->with($newPassword);

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldReceive('save')->once();
        $userRepository->shouldReceive('findOneBy')->once()->andReturn($user);

        $eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldReceive('dispatch')->once();

        $encoder = \Mockery::mock(UserPasswordEncoder::class);
        $encoder->shouldReceive('isPasswordValid')->once()->andReturn(true);

        $handler = new UpdateUserPasswordHandler($userRepository, $eventDispatcher, $encoder);

        $handler->handle($command);
    }

    public function testUpdateUserPasswordHandlerWithInvalidPassword()
    {
        $username = "Username";
        $oldPassword = "";
        $newPassword = "NewPassword";

        $command = new UpdateUserPassword($username, $oldPassword, $newPassword);

        $user = \Mockery::mock(User::class);
        $user->shouldReceive('setPlainPassword')->never();

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldReceive('save')->never();
        $userRepository->shouldReceive('findOneBy')->once()->andReturn($user);

        $eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldReceive('dispatch')->never();

        $encoder = \Mockery::mock(UserPasswordEncoder::class);
        $encoder->shouldReceive('isPasswordValid')->once()->andReturn(false);

        $handler = new UpdateUserPasswordHandler($userRepository, $eventDispatcher, $encoder);

        try {
            $handler->handle($command);
        } catch (ValidatorException $e) {
            $this->assertContains("Cannot update password for user, because of invalid credentials", $e->getMessage());
        }
    }

    public function testUpdateUserPasswordHandlerWithNonexistentUser()
    {
        $username = "Username123";
        $oldPassword = "Password";
        $newPassword = "NewPassword";

        $command = new UpdateUserPassword($username, $oldPassword, $newPassword);

        $user = \Mockery::mock(User::class);
        $user->shouldReceive('setPlainPassword')->never();

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldReceive('save')->never();
        $userRepository->shouldReceive('findOneBy')->once()->andReturn(false);

        $eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldReceive('dispatch')->never();

        $encoder = \Mockery::mock(UserPasswordEncoder::class);
        $encoder->shouldReceive('isPasswordValid')->never();

        $handler = new UpdateUserPasswordHandler($userRepository, $eventDispatcher, $encoder);

        try {
            $handler->handle($command);
        } catch (EntityNotFoundException $e) {
            $this->assertContains("No entity found for username", $e->getMessage());
        }
    }
}
