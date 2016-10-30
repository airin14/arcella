<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\UtilityBundle\TokenValidator;

use Arcella\UtilityBundle\Entity\Token;
use Arcella\UtilityBundle\Repository\TokenRepository;
use Arcella\UtilityBundle\TokenValidator\TokenValidator;
use Doctrine\ORM\EntityNotFoundException;

class TokenValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateToken()
    {
        $tokenRepository = \Mockery::mock(TokenRepository::class);
        $tokenRepository->shouldReceive("save")->once();

        $length = 5;
        $keyspace = "1234567890";
        $lifespan = 42;

        $params = array(
            "foo" => "bar"
        );

        $tokenValidator = new TokenValidator($tokenRepository, $length, $keyspace, $lifespan);
        $result = $tokenValidator->generateToken($params);

        $this->assertRegExp("/^[0-9]{5,5}$/", $result);
    }

    public function testValidateToken()
    {
        $length = 5;
        $keyspace = "1234567890";
        $lifespan = '42';

        $tomorrow = new \DateTime('tomorrow');
        $params = array(
            "foo" => "bar"
        );

        $token = \Mockery::mock(Token::class);
        $token->shouldReceive("getExpiration")->once()->andReturn($tomorrow);
        $token->shouldReceive("getParams")->once()->andReturn($params);

        $tokenRepository = \Mockery::mock(TokenRepository::class);
        $tokenRepository->shouldReceive("findOneByKey")->once()->andReturn($token);

        $tokenValidator = new TokenValidator($tokenRepository, $length, $keyspace, $lifespan);

        $empty_result = $tokenValidator->getTokenParams();
        $this->assertTrue(is_null($empty_result));

        $this->assertTrue($tokenValidator->validateToken("12345"));

        $result = $tokenValidator->getTokenParams();
        $this->assertArrayHasKey("foo", $result);
    }

    public function testValidateInvalidToken()
    {
        $this->expectException(EntityNotFoundException::class);

        $length = 5;
        $keyspace = "1234567890";
        $lifespan = '42';

        $tokenRepository = \Mockery::mock(TokenRepository::class);
        $tokenRepository->shouldReceive("findOneByKey")->once()->andReturn(false);

        $tokenValidator = new TokenValidator($tokenRepository, $length, $keyspace, $lifespan);

        $tokenValidator->validateToken("12345");
    }

    public function testValidateExpiredToken()
    {
        $length = 5;
        $keyspace = "1234567890";
        $lifespan = '42';

        $yesterday = new \DateTime('yesterday');
        $params = array(
            "foo" => "bar"
        );

        $token = \Mockery::mock(Token::class);
        $token->shouldReceive("getExpiration")->once()->andReturn($yesterday);
        $token->shouldReceive("getParams")->once()->andReturn($params);

        $tokenRepository = \Mockery::mock(TokenRepository::class);
        $tokenRepository->shouldReceive("findOneByKey")->once()->andReturn($token);

        $tokenValidator = new TokenValidator($tokenRepository, $length, $keyspace, $lifespan);

        $this->assertFalse($tokenValidator->validateToken("12345"));
    }

    public function testRemoveToken()
    {
        $length = 5;
        $keyspace = "1234567890";
        $lifespan = '42';

        $token = \Mockery::mock(Token::class);

        $tokenRepository = \Mockery::mock(TokenRepository::class);
        $tokenRepository->shouldReceive("findOneByKey")->once()->andReturn($token);
        $tokenRepository->shouldReceive("delete")->once();

        $tokenValidator = new TokenValidator($tokenRepository, $length, $keyspace, $lifespan);
        $tokenValidator->removeToken("12345");
    }

    public function testRemoveInvalidToken()
    {
        $this->expectException(EntityNotFoundException::class);

        $length = 5;
        $keyspace = "1234567890";
        $lifespan = '42';

        $tokenRepository = \Mockery::mock(TokenRepository::class);
        $tokenRepository->shouldReceive("findOneByKey")->once()->andReturn(false);
        $tokenRepository->shouldNotReceive("delete");

        $tokenValidator = new TokenValidator($tokenRepository, $length, $keyspace, $lifespan);
        $tokenValidator->removeToken("12345");
    }
}
