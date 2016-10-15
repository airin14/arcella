<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\Utils;

use Arcella\UserBundle\Entity\Token;
use Arcella\UserBundle\Repository\TokenRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Validator\Constraints\Time;

/**
 * Class TokenValidator
 */
class TokenValidator
{
    private $em;

    private $keyspace;

    private $length;

    /**
     * TokenValidator constructor.
     *
     * @param TokenRepository $em
     * @param integer         $length
     * @param string          $keyspace
     * @param Time            $lifespan
     */
    public function __construct(TokenRepository $em, $length, $keyspace, $lifespan)
    {
        $this->em       = $em;
        $this->keyspace = $keyspace;
        $this->length   = $length;
        $this->lifespan = $lifespan;
    }

    /**
     * Create a individual $salt, which is a static function to be accessible
     * throughout the application.
     *
     * @param string  $keyspace Includes all characters, numbers and special
     *                          signs that can be used to create the token.
     * @param integer $length   Total length of the token.
     *
     * @return string $token The individual $salt of the $token.
     */
    public static function createSalt($keyspace, $length)
    {
        $token = '';
        $max = mb_strlen($keyspace, '8bit') - 1;

        for ($i = 0; $i < $length; ++$i) {
            $token .= $keyspace[random_int(0, $max)];
        }

        return $token;
    }

    /**
     * Generate a $token and store it in the Repository.
     *
     * @param string $lifespan The lifespan of the token.
     *
     * @return string $token    The key of the token.
     */
    public function generateToken($lifespan = '')
    {
        if (empty($lifespan)) {
            $lifespan = $this->lifespan;
        }

        $expiration = new \DateInterval('PT'.$lifespan.'M');
        $date = new \DateTime();
        $date->add($expiration);

        $token = new Token();
        $token->setKey(self::createSalt($this->keyspace, $this->length));
        $token->setExpiration($date);

        $this->em->save($token);

        return $token->getKey();
    }

    /**
     * Validate a $token against the Repository.
     *
     * @param string $token The key of the token to be validated.
     *
     * @return bool
     *
     * @throws EntityNotFoundException
     */
    public function validateToken($token)
    {
        $token = $this->em->findOneByKey($token);

        if (!$token) {
            throw new EntityNotFoundException(
                'No token found for key '.$token
            );
        }

        $now = new \DateTime();

        if ($now <= $token->getExpiration()) {
            return false;
        }

        return true;
    }

    /**
     * Remove a $token from the Repository.
     *
     * @param string $token The key of the token to be removed.
     *
     * @throws EntityNotFoundException
     */
    public function removeToken($token)
    {
        $token = $this->em->findOneByKey($token);

        if (!$token) {
            throw new EntityNotFoundException(
                'No token found for key '.$token
            );
        }

        $this->em->delete($token);
    }
}
