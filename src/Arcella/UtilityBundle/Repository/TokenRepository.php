<?php

namespace Arcella\UtilityBundle\Repository;

use Arcella\UtilityBundle\Entity\Token;
use Doctrine\ORM\EntityRepository;

/**
 * TokenRepository
 */
class TokenRepository extends EntityRepository
{
    /**
     * @param Token $token
     */
    public function save(Token $token)
    {
        $this->_em->persist($token);
        $this->_em->flush();
    }

    /**
     * @param Token $token
     */
    public function delete(Token $token)
    {
        $this->_em->remove($token);
        $this->_em->flush();
    }
}
