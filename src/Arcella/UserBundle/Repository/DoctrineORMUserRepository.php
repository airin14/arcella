<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\Repository;

use Arcella\Domain\Repository\UserRepositoryInterface;
use Arcella\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Class DoctrineORMUserRepository
 *
 * @package Arcella\UserBundle\Repository
 */
class DoctrineORMUserRepository extends EntityRepository implements UserRepositoryInterface
{
    /**
     * Add a User entity to the repository.
     *
     * @param User $user The entity to be added.
     */
    public function add(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
