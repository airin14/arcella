<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Application\Handler;

use Arcella\Application\Handler\RecoverPasswordHandler;
use Arcella\Domain\Command\RecoverPassword;
use Arcella\Domain\Entity\User;
use Arcella\Domain\Repository\UserRepositoryInterface;
use Arcella\UtilityBundle\TokenValidator\TokenValidator;
use Doctrine\ORM\EntityNotFoundException;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RecoverPasswordHandlerTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

    public function testRecoverPasswordHandler()
    {
        $username = "foo";
        $email = "foo@bar.com";
        $token = "tok3n";

        $command = \Mockery::mock(RecoverPassword::class);
        $command->shouldReceive('email')->once()->andReturn($email);

        $user = \Mockery::mock(User::class);
        $user->shouldReceive('getUsername')->twice()->andReturn($username);
        $user->shouldReceive('getEmail')->once()->andReturn($email);

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldReceive('findOneBy')->once()->andReturn($user);

        $mailer = \Mockery::mock(\Swift_Mailer::class);
        $mailer->shouldReceive('send')->once();

        $twig = \Mockery::mock(\Twig_Environment::class);
        $twig->shouldReceive('render')->once()->andReturn("Content");

        $validator = \Mockery::mock(TokenValidator::class);
        $validator->shouldReceive('generateToken')->once()->andReturn($token);

        $eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldReceive('dispatch')->once();

        $handler = new RecoverPasswordHandler($userRepository, $mailer, $twig, $validator, $eventDispatcher);

        $handler->handle($command);
    }

    public function testRecoverPasswordHandlerWithNonexistentUser()
    {
        $username = "nonexistentuser";
        $email = "no@user.com";
        $token = "tok3n";

        $command = \Mockery::mock(RecoverPassword::class);
        $command->shouldReceive('email')->twice()->andReturn($email);

        $user = \Mockery::mock(User::class);
        $user->shouldReceive('getUsername')->never();

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldReceive('findOneBy')->once()->andReturn(false);

        $mailer = \Mockery::mock(\Swift_Mailer::class);
        $mailer->shouldNotReceive('send');

        $twig = \Mockery::mock(\Twig_Environment::class);
        $twig->shouldNotReceive('render');

        $validator = \Mockery::mock(TokenValidator::class);
        $validator->shouldNotReceive('generateToken');

        $eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldNotReceive('dispatch');

        $handler = new RecoverPasswordHandler($userRepository, $mailer, $twig, $validator, $eventDispatcher);

        try {
            $handler->handle($command);
        } catch (EntityNotFoundException $e) {
            $this->assertContains("No entity found for email address", $e->getMessage());
        }
    }
}
