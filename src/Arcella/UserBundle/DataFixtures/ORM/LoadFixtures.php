<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\DataFixtures\ORM;

use Arcella\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

/**
 * Implements all necessary logic to load some fixtures for the app.
 */
class LoadFixtures implements FixtureInterface
{
    /**
     * Loads fixtures for the UserBundle
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $objects = Fixtures::load(__DIR__.'/users.yml', $manager, ['providers' => [$this]]);
    }

    /**
     * Just add the ROLE_USER role to a user
     *
     * @return array Containing only the ROLE_USER role
     */
    public function roleUser()
    {
        return array("ROLE_USER");
    }
}
