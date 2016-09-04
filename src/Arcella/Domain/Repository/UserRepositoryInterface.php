<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Domain\Repository;

use Arcella\Domain\Entity\User;

/**
 * This is the Interface for the UserRepository
 *
 * @package Arcella\Domain\Repository
 */
interface UserRepositoryInterface
{
    /**
     * Add a User entity to the repository.
     *
     * @param User $user The entity to be added.
     */
    public function add(User $user);
}
