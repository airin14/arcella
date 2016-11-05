<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Application\Handler;

use Arcella\Application\Handler\RecoverPasswordHandler;
use Arcella\Application\Handler\ResetPasswordHandler;
use Arcella\Domain\Command\RecoverPassword;
use Arcella\Domain\Command\ResetPassword;
use Arcella\Domain\Entity\User;
use Arcella\Domain\Event\ResetPasswordEvent;
use Arcella\Domain\Repository\UserRepositoryInterface;
use Arcella\UtilityBundle\Entity\Token;
use Arcella\UtilityBundle\Repository\TokenRepository;
use Arcella\UtilityBundle\TokenValidator\TokenValidator;
use Doctrine\ORM\EntityNotFoundException;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Validator\Exception\ValidatorException;

class ResetPasswordHandlerTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

    public function testResetPasswordHandler()
    {
        $username = "foo";
        $newPassword = "newPassword";
        $token = "tok3n";
        $params = array('username' => $username);

        $command = \Mockery::mock(ResetPassword::class);
        $command->shouldReceive('token')->twice()->andReturn($token);
        $command->shouldReceive('newPassword')->once()->andReturn($newPassword);

        $user = \Mockery::mock(User::class);
        $user->shouldReceive('setPlainPassword')->once();

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldReceive('findOneBy')->once()->andReturn($user);
        $userRepository->shouldReceive('save')->once();

        $validator = \Mockery::mock(TokenValidator::class);
        $validator->shouldReceive('validateToken')->once()->andReturn(true);
        $validator->shouldReceive('removeToken')->once();
        $validator->shouldReceive('getTokenParams')->once()->andReturn($params);

        $eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldReceive('dispatch')->once();

        $handler = new ResetPasswordHandler($userRepository, $eventDispatcher, $validator);

        $handler->handle($command);
    }

    public function testResetPasswordHandlerWithInvalidToken()
    {
        $token = "inv4lid";

        $command = \Mockery::mock(ResetPassword::class);
        $command->shouldReceive('token')->twice()->andReturn($token);
        $command->shouldNotReceive('newPassword');

        $user = \Mockery::mock(User::class);
        $user->shouldNotReceive('setPlainPassword');

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldNotReceive('findOneBy');
        $userRepository->shouldNotReceive('save');

        $validator = \Mockery::mock(TokenValidator::class);
        $validator->shouldReceive('validateToken')->once()->andReturn(false);
        $validator->shouldNotReceive('getTokenParams');

        $eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldNotReceive('dispatch');

        $handler = new ResetPasswordHandler($userRepository, $eventDispatcher, $validator);

        try {
            $handler->handle($command);
        } catch (ValidatorException $e) {
            $this->assertContains("Could not validate token", $e->getMessage());
        }
    }

    public function testResetPasswordHandlerWithNonexistantUser()
    {
        $username = "foo";
        $token = "tok3n";
        $params = array('username' => $username);

        $command = \Mockery::mock(ResetPassword::class);
        $command->shouldReceive('token')->once()->andReturn($token);
        $command->shouldNotReceive('newPassword');

        $user = \Mockery::mock(User::class);
        $user->shouldNotReceive('setPlainPassword');

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldReceive('findOneBy')->once()->andReturn(false);
        $userRepository->shouldNotReceive('save');

        $validator = \Mockery::mock(TokenValidator::class);
        $validator->shouldReceive('validateToken')->once()->andReturn(true);
        $validator->shouldNotReceive('removeToken');
        $validator->shouldReceive('getTokenParams')->once()->andReturn($params);

        $eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldNotReceive('dispatch');

        $handler = new ResetPasswordHandler($userRepository, $eventDispatcher, $validator);

        try {
            $handler->handle($command);
        } catch (EntityNotFoundException $e) {
            $this->assertContains("No entity found for username", $e->getMessage());
        }
    }
}
