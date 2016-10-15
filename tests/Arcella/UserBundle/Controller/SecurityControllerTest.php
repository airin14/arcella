<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\Arcella\UserBundle\Controller;

use Arcella\Test\ArcellaWebTestCase;

class SecurityControllerTest extends ArcellaWebTestCase
{
    public function testLoginAction()
    {
        // Check if the login form can be accessed
        $crawler = $this->client->request('GET', '/login');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Login")')->count());

        // Fill in and submit the form
        $form = $crawler->filter('form[name=login_form]')->form();
        $crawler = $this->client->submit($form, array(
            'login_form[username]' => 'monty93',
            'login_form[password]' => 'arcella',
        ));

        // Fetch the response
        $response = $this->client->getResponse()->getContent();

        // Assertions
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContains('Logged in as', $response);
    }

    public function testLogoutAction()
    {
        $crawler = $this->client->request('GET', '/logout');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Login")')->count());
    }
}