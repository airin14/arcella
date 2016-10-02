<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Application\Handler;

use Arcella\Application\Handler\ValidateUserEmailHandler;
use Arcella\Domain\Command\ValidateUserEmail;
use Arcella\Domain\Entity\User;
use Arcella\Domain\Repository\UserRepositoryInterface;
use Arcella\UserBundle\Utils\TokenValidator;
use Doctrine\ORM\EntityNotFoundException;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Symfony\Component\Validator\Exception\ValidatorException;

class ValidateUserEmailHandlerTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

    public function testValidateUserEmailHandler()
    {
        $username = "Username";
        $email = "foo@bar.com";

        $command = new ValidateUserEmail($username, $email);

        $user = \Mockery::mock(User::class);
        $user->shouldReceive('setEmailIsVerified')->once();

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldReceive('save')->once();
        $userRepository->shouldReceive('findOneBy')->once()->andReturn($user);

        $validator = \Mockery::mock(TokenValidator::class);
        $validator->shouldReceive('validateToken')->once()->andReturn(true);
        $validator->shouldReceive('removeToken')->once();

        $handler = new ValidateUserEmailHandler($userRepository, $validator);

        $handler->handle($command);
    }

    public function testValidateUserEmailHandlerWithNonexistentUser()
    {
        $username = "Username123";
        $email = "foo@bar.com";

        $command = new ValidateUserEmail($username, $email);

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldReceive('save')->never();
        $userRepository->shouldReceive('findOneBy')->once()->andReturn(false);

        $validator = \Mockery::mock(TokenValidator::class);
        $validator->shouldReceive('validateToken')->never();
        $validator->shouldReceive('removeToken')->never();

        $handler = new ValidateUserEmailHandler($userRepository, $validator);

        try {
            $handler->handle($command);
        } catch (EntityNotFoundException $e) {
            $this->assertContains("No entity found for username", $e->getMessage());
        }
    }

    public function testValidateUserEmailHandlerWithInvalidToken()
    {
        $username = "Username123";
        $email = "foo@bar.com";

        $command = new ValidateUserEmail($username, $email);

        $user = \Mockery::mock(User::class);
        $user->shouldReceive('setEmailIsVerified')->never();

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldReceive('save')->never();
        $userRepository->shouldReceive('findOneBy')->once()->andReturn($user);

        $validator = \Mockery::mock(TokenValidator::class);
        $validator->shouldReceive('validateToken')->once()->andReturn(false);
        $validator->shouldReceive('removeToken')->never();

        $handler = new ValidateUserEmailHandler($userRepository, $validator);

        try {
            $handler->handle($command);
        } catch (ValidatorException $e) {
            $this->assertContains("Could not validate token", $e->getMessage());
        }
    }
}
