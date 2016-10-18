<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Domain\Entity;

use Arcella\UtilityBundle\Entity\Token;
use Arcella\Domain\Exception\DomainException;

class TokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Token $token
     */
    private $token;

    public function setUp()
    {
        $this->token = new Token();
    }

    public function testKey()
    {
        $key = "tok3n";
        $this->token->setKey($key);

        $this->assertEquals($key, $this->token->getKey());
    }

    public function testExpiration()
    {
        $time = new \DateTime();
        $this->token->setExpiration($time);

        $this->assertEquals($time, $this->token->getExpiration());
    }

    public function testParams()
    {
        $params = array('foo' => 'bar');
        $this->token->setParams($params);

        $temp = $this->token->getParams();

        $this->assertEquals($params['foo'], $temp['foo']);
    }
}
