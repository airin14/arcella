<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\Application\Handler;

use Arcella\Domain\Entity\User;
use Arcella\Domain\Repository\UserRepositoryInterface;

class MockUserRepository implements UserRepositoryInterface
{
    private $user;

    public function add(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
}
