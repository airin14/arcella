<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\UserBundle\Controller;

trait UserBackendTrait
{
    private function accessBackend()
    {
        // Check if the settings page can be accessed
        $crawler = $this->client->request('GET', '/_settings');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Settings")')->count());
    }
}