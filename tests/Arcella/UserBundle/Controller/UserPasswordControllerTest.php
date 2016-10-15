<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\Controller;

use Arcella\Test\ArcellaWebTestCase;

class UserPasswordControllerTest extends ArcellaWebTestCase
{
    public function testUpdatePasswordAction()
    {
        $this->loginAsUser();

        // Check if the login form can be accessed
        $crawler = $this->client->request('GET', '/_settings/password');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Change your password")')->count());

        // Fill in and submit the form
        $form = $crawler->filter('form[name=user_update_password_form]')->form();
        $crawler = $this->client->submit($form, array(
            'user_update_password_form[oldPassword]' => 'arcella',
            'user_update_password_form[newPassword][first]' => 'arcella',
            'user_update_password_form[newPassword][second]' => 'arcella',
        ));

        // Fetch the response
        $response = $this->client->getResponse()->getContent();

        // Assertions
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContains('Your password has been updated successfully.', $response);
    }

    private function loginAsUser()
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
}
