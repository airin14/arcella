<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\Repository;

use Arcella\Domain\Repository\UserRepositoryInterface;
use Arcella\Domain\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 *
 * @package Arcella\UserBundle\Repository
 */
class UserRepository extends EntityRepository implements UserRepositoryInterface
{
    /**
     * Add a User entity to the repository.
     *
     * @param User $user The entity to be added.
     */
    public function add(User $user)
    {
        $this->save($user);
    }

    /**
     * Saves a User entity in the repository.
     *
     * @param User $user The entity to be saved.
     */
    public function save(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
