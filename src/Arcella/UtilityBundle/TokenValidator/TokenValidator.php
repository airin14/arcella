<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UtilityBundle\TokenValidator;

use Arcella\UtilityBundle\Entity\Token;
use Arcella\UtilityBundle\Repository\TokenRepository;
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

    private $lifespan;

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
        $this->length   = $length;
        $this->keyspace = $keyspace;
        $this->lifespan = $lifespan;
    }

    /**
     * Create a individual $salt, which is a static function to be accessible
     * throughout the application.
     *
     * @param int    $length
     * @param string $keyspace
     *
     * @return string $token The individual $salt of the $token.
     */
    public function createSalt($length = 0, $keyspace = "")
    {
        if ($length === 0) {
            $length = $this->length;
        }

        if (empty($keyspace)) {
            $keyspace = $this->keyspace;
        }

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
     * @param array  $params   Some parameters for the token
     * @param string $lifespan The lifespan of the token.
     *
     * @return string $token    The key of the token.
     */
    public function generateToken($params = array(), $lifespan = '')
    {
        if (empty($lifespan)) {
            $lifespan = $this->lifespan;
        }

        $expiration = new \DateInterval('PT'.$lifespan.'M');
        $date = new \DateTime();
        $date->add($expiration);

        $token = new Token();
        $token->setKey($this->createSalt());
        $token->setExpiration($date);
        $token->setParams($params);

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
