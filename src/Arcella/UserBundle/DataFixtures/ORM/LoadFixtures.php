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
 * Class LoadFixtures
 *
 * Implements all necessary logic to load some fixtures for the app.
 *
 * @package Arcella\UserBundle\DataFixtures\ORM
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
        $objects = Fixtures::load(__DIR__.'/users.yml', $manager);
    }
}
