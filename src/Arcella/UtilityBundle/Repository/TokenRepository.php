<?php

namespace Arcella\UtilityBundle\Repository;

use Arcella\UtilityBundle\Entity\Token;
use Doctrine\ORM\EntityRepository;

/**
 * The TokenRepository is responsible for storing the Token entities.
 */
class TokenRepository extends EntityRepository
{
    /**
     * Save a given Token entity in the TokenRepository.
     *
     * @param Token $token The actual Token entity that should be saved.
     */
    public function save(Token $token)
    {
        $this->_em->persist($token);
        $this->_em->flush();
    }

    /**
     * Remove a given Token entity from the TokenRepository.
     *
     * @param Token $token The Token entity that should be deleted.
     */
    public function delete(Token $token)
    {
        $this->_em->remove($token);
        $this->_em->flush();
    }
}
