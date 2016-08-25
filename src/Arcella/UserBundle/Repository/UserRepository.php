<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\Repository;

use Arcella\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class DoctrineORMUserRepository
 *
 * @package Arcella\UserBundle\Repository
 */
class DoctrineORMUserRepository extends EntityRepository implements UserRepositoryInterface
{

}
