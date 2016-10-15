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
        $token = "validToken";

        $command = \Mockery::mock(ValidateUserEmail::class);
        $command->shouldReceive('username')->once()->andReturn($username);
        $command->shouldReceive('token')->twice()->andReturn($token);

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

        $command = \Mockery::mock(ValidateUserEmail::class);
        $command->shouldReceive('username')->twice()->andReturn($username);
        $command->shouldNotReceive('token');

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldNotReceive('save');
        $userRepository->shouldReceive('findOneBy')->once()->andReturn(false);

        $validator = \Mockery::mock(TokenValidator::class);
        $validator->shouldNotReceive('validateToken');
        $validator->shouldNotReceive('removeToken');

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
        $token = "invalidToken";

        $command = \Mockery::mock(ValidateUserEmail::class);
        $command->shouldReceive('username')->once()->andReturn($username);
        $command->shouldReceive('token')->twice()->andReturn($token);

        $user = \Mockery::mock(User::class);
        $user->shouldNotReceive('setEmailIsVerified');

        $userRepository = \Mockery::mock(UserRepositoryInterface::class);
        $userRepository->shouldNotReceive('save');
        $userRepository->shouldReceive('findOneBy')->once()->andReturn($user);

        $validator = \Mockery::mock(TokenValidator::class);
        $validator->shouldReceive('validateToken')->once()->andReturn(false);
        $validator->shouldNotReceive('removeToken');

        $handler = new ValidateUserEmailHandler($userRepository, $validator);

        try {
            $handler->handle($command);
        } catch (ValidatorException $e) {
            $this->assertContains("Could not validate token", $e->getMessage());
        }
    }
}
