<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\UserBundle\Controller;

use Arcella\Test\ArcellaWebTestCase;

class SecurityControllerTest extends ArcellaWebTestCase
{
    use UserLoginTrait;

    public function testLoginAction()
    {
        $username = "monty93";
        $password = "arcella";

        $this->doLogin($username, $password);
        $response = $this->client->getResponse()->getContent();

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContains('Logged in as', $response);
    }

    public function testLogoutAction()
    {
        $crawler = $this->client->request('GET', '/logout');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Login")')->count());
    }
}