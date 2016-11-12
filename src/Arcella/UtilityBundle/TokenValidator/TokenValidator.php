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

/**
 * The TokenValidator is used to validate specific requests made by the users.
 *
 * For example it could be used to ensure that a user has provided an email
 * address that he has access to. Therefore you'd have to create a Token, send
 * it to the email address and ask the user to tell you what the Token was.
 */
class TokenValidator
{
    /**
     * @var TokenRepository
     */
    private $em;

    /**
     * @var string
     */
    private $keyspace;

    /**
     * @var int
     */
    private $length;

    /**
     * @var int
     */
    private $lifespan;

    /**
     * @var mixed
     */
    private $tokenParams;

    /**
     * TokenValidator constructor.
     *
     * @param TokenRepository $em
     * @param integer         $length
     * @param string          $keyspace
     * @param integer         $lifespan
     */
    public function __construct(TokenRepository $em, $length, $keyspace, $lifespan)
    {
        $this->em       = $em;
        $this->length   = $length;
        $this->keyspace = $keyspace;
        $this->lifespan = $lifespan;
    }

    /**
     * Create a individual $salt.
     *
     * @param int    $length
     * @param string $keyspace
     *
     * @return string $salt The individual $salt.
     */
    public function createSalt($length = 0, $keyspace = "")
    {
        if ($length === 0) {
            $length = $this->length;
        }

        if (empty($keyspace)) {
            $keyspace = $this->keyspace;
        }

        $salt = '';
        $max = mb_strlen($keyspace, '8bit') - 1;

        for ($i = 0; $i < $length; ++$i) {
            $salt .= $keyspace[random_int(0, $max)];
        }

        return $salt;
    }

    /**
     * Generate a $token and store it in the TokenRepository.
     *
     * @param array  $params   Some parameters for the token
     * @param string $lifespan The lifespan of the token in seconds.
     *
     * @return string $token   The key of the token.
     */
    public function generateToken($params = array(), $lifespan = null)
    {
        if (is_null($lifespan)) {
            $lifespan = $this->lifespan;
        }

        $expiration = new \DateInterval('PT'.$lifespan.'S');

        $date = new \DateTime("now");
        $date->add($expiration);

        $token = new Token();
        $token->setKey($this->createSalt());
        $token->setExpiration($date);
        $token->setParams($params);

        $this->em->save($token);

        return $token->getKey();
    }

    /**
     * Validate a Token entity.
     *
     * @param string $key The key of the Token entity to be validated.
     *
     * @return bool Was the validation successful?
     *
     * @throws EntityNotFoundException
     */
    public function validateToken($key)
    {
        $token = $this->em->findOneByKey($key);

        if (!$token) {
            throw new EntityNotFoundException(
                'No token found for key '.$token
            );
        }

        $now = new \DateTime("now");

        if ($now >= $token->getExpiration()) {
            return false;
        }

        $this->tokenParams = $token->getParams();

        return true;
    }

    /**
     * Returns the $tokenParams if any are set, after a Token has been
     * validated.
     *
     * @return array|bool Returns false when no $tokenParams are set.
     */
    public function getTokenParams()
    {
        if (empty($this->tokenParams)) {
            return null;
        }

        return $this->tokenParams;
    }

    /**
     * Remove a Token entity from the TokenRepository.
     *
     * @param string $key The key of the token entity to be removed.
     *
     * @throws EntityNotFoundException
     */
    public function removeToken($key)
    {
        $token = $this->em->findOneByKey($key);

        if (!$token) {
            throw new EntityNotFoundException(
                'No token found for key '.$token
            );
        }

        $this->em->delete($token);
    }
}
