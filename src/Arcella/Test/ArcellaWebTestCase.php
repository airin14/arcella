<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ArcellaWebTestCase
 */
class ArcellaWebTestCase extends WebTestCase
{
    /**
     * @var $client
     */
    public $client;

    /**
     * Creates the Goutte\Client.
     *
     * This library manages all the http stuff and brings the crawler to this testsuite. See also:
     * https://github.com/FriendsOfPHP/Goutte
     */
    protected function setUp()
    {
        $this->client = parent::createClient();
        $this->client->followRedirects();
    }
}
