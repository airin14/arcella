<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\Arcella\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    protected $client;

    /**
     * Creates the Goutte\Client.
     *
     * This library manages all the http stuff and brings the crawler to this testsuite. See also:
     * https://github.com/FriendsOfPHP/Goutte
     */
    protected function setUp()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
    }

    public function testRegisterAction()
    {
        // Check if the login form can be accessed
        $crawler = $this->client->request('GET', '/register');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Register!")')->count());

        // Fill in and submit the form
        $form = $crawler->filter('form[name=user_registration_form]')->form();
        $crawler = $this->client->submit($form, array(
            'user_registration_form[username]' => 'foobar',
            'user_registration_form[email]' => 'foo@bar.com',
            'user_registration_form[plainPassword][first]' => 'arcella',
            'user_registration_form[plainPassword][second]' => 'arcella',
        ));

        // Fetch the response
        $response = $this->client->getResponse()->getContent();

        // Assertions
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContains('Logged in as', $response);
    }
}