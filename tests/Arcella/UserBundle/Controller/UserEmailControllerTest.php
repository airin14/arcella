<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Arcella\UserBundle\Controller;

use Arcella\Test\ArcellaWebTestCase;

class UserEmailControllerTest extends ArcellaWebTestCase
{
    use UserLoginTrait;

    public function testUpdateEmailAction()
    {
        $username    = "monty93";
        $password    = "arcella";
        $newEmail    = 'foo@bar' . rand() . '.com';

        $this->client->followRedirects();

        $this->doLogin($username, $password);
        $this->doEmailUpdate($newEmail);

        // Fetch the response
        $response = $this->client->getResponse()->getContent();

        // Assertions
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContains("Your email address has been updated successfully", $response);
    }

    public function testAccessToUpdateEmailAction()
    {
        $this->client->followRedirects();

        $crawler = $this->client->request('GET', '/_settings/email');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Login")')->count());
    }

    private function doEmailUpdate($newEmail)
    {
        // Check if the update form can be accessed
        $crawler = $this->client->request('GET', '/_settings/email');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Change your email address")')->count());

        // Fill in and submit the form
        $form = $crawler->filter('form[name=user_update_email_form]')->form();
        $crawler = $this->client->submit($form, array(
            'user_update_email_form[email]' => $newEmail,
        ));
    }
}
